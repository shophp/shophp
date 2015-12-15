<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\Session;
use Nette\Http\SessionSection;

class OrderService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $orderRepository;

	/** @var SessionSection */
	private $orderSession;

	public function __construct(EntityManagerInterface $entityManager, Session $session)
	{
		parent::__construct($entityManager);
		$this->orderRepository = $entityManager->getRepository(Order::class);
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
			return $this->orderRepository->find($this->orderSession->orderId);
		}
		return null;
	}

	public function create(Order $order)
	{
		$this->createEntity($order);
		$this->orderSession->orderId = $order->getId();
	}

}
