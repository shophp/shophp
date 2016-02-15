<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;
use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Payment\PaymentType;

class OrderService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var SessionSection */
	private $orderSession;

	/** @var bool */
	private $cashPaymentAvailable;

	/** @var bool */
	private $bankPaymentAvailable;

	/** @var bool */
	private $cardPaymentAvailable;

	public function __construct(
		EntityManagerInterface $entityManager,
		Session $session,
		$cashPaymentAvailable,
		$bankPaymentAvailable,
		$cardPaymentAvailable
	)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(Order::class);
		$this->entityManager = $entityManager;
		$orderSession = $session->getSection('order');
		$orderSession->setExpiration('+ 30 minutes');
		$this->orderSession = $orderSession;

		$this->cashPaymentAvailable = (bool) $cashPaymentAvailable;
		$this->bankPaymentAvailable = (bool) $bankPaymentAvailable;
		$this->cardPaymentAvailable = (bool) $cardPaymentAvailable;
	}

	public function isPaymentTypeAvailable(PaymentType $paymentType)
	{
		switch ($paymentType->getValue()) {
			case PaymentType::CASH:
				return $this->cashPaymentAvailable;
			case PaymentType::BANK:
				return $this->bankPaymentAvailable;
			case PaymentType::CARD:
				return $this->cardPaymentAvailable;
			default:
				throw new \LogicException();
		}
	}

	/**
	 * @return Order
	 */
	public function getLastOrder()
	{
		if ($this->orderSession->orderId !== null) {
			return $this->repository->find($this->orderSession->orderId);
		}
		return null;
	}

	public function createFromCart(Cart $cart, PaymentType $paymentType)
	{
		if (!$this->isPaymentTypeAvailable($paymentType)) {
			throw new EntityInvalidArgumentException(sprintf('Payment type %s is not available.', $paymentType->getLabel()));
		}

		do {
			$number = Random::generate(Order::NUMBER_LENGTH, '0-9');
		} while ($this->existsOrderWithNumber($number));

		$order = new Order($cart, $number, $paymentType);
		$this->createEntity($order);
		$this->orderSession->orderId = $order->getId();
		return $order;
	}

	/**
	 * @return Order[]|\Traversable
	 */
	public function getAll($limit, $offset)
	{
		$query = $this->entityManager->createQuery(sprintf(
			'SELECT o FROM %s o',
			Order::class
		));

		$query->setFirstResult($offset)
			->setMaxResults($limit);

		return new Paginator($query);
	}

	private function existsOrderWithNumber($number)
	{
		$duplicate = $this->repository->findOneBy([
			'number' => $number,
		]);
		return $duplicate !== null;
	}

}
