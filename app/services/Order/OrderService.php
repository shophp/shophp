<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;

class OrderService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	/** @var SessionSection */
	private $orderSession;

	public function __construct(EntityManagerInterface $entityManager, Session $session)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(Order::class);
		$orderSession = $session->getSection('order');
		$orderSession->setExpiration('+ 30 minutes');
		$this->orderSession = $orderSession;
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

	public function createFromCart(Cart $cart)
	{
		do {
			$number = Random::generate(Order::NUMBER_LENGTH, '0-9');
		} while ($this->existsOrderWithNumber($number));

		$order = new Order($cart, $number);
		$this->createEntity($order);
		$this->orderSession->orderId = $order->getId();
		return $order;
	}

	private function existsOrderWithNumber($number)
	{
		$duplicate = $this->repository->findOneBy([
			'number' => $number,
		]);
		return $duplicate !== null;
	}

}
