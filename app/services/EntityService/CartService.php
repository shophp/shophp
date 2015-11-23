<?php

namespace ShoPHP;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\Session;
use Nette\Http\SessionSection;

class CartService extends \ShoPHP\EntityService
{

	/** @var SessionSection */
	private $cartSession;

	public function __construct(EntityManagerInterface $entityManager, Session $session)
	{
		parent::__construct($entityManager->getRepository(Cart::class), $entityManager);
		$this->cartSession = $session->getSection('cart');
	}

	public function getCurrentCart()
	{
		if ($this->cartSession->cartId !== null) {
			$cart = $this->repository->find($this->cartSession->cartId);
			if ($cart !== null) {
				return $cart;
			}
		}
		return new Cart();
	}

	public function save(Cart $cart)
	{
		if (count($cart->getItems()) > 0) {
			if ($cart->getId() === null) {
				$this->createEntity($cart);
				$this->cartSession->cartId = $cart->getId();
			} else {
				$this->updateEntity($cart);
			}
		}
	}

}
