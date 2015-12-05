<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\Session;
use Nette\Http\SessionSection;

class CartService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $cartRepository;

	/** @var ObjectRepository */
	private $itemRepository;

	/** @var SessionSection */
	private $cartSession;

	public function __construct(EntityManagerInterface $entityManager, Session $session)
	{
		parent::__construct($entityManager);
		$this->cartRepository = $entityManager->getRepository(Cart::class);
		$this->itemRepository = $entityManager->getRepository(CartItem::class);
		$this->cartSession = $session->getSection('cart');
	}

	public function getCurrentCart()
	{
		if ($this->cartSession->cartId !== null) {
			$cart = $this->cartRepository->find($this->cartSession->cartId);
			if ($cart !== null) {
				return $cart;
			}
		}
		return new Cart();
	}

	/**
	 * @param int $id
	 * @return CartItem|null
	 */
	public function getItemById($id)
	{
		return $this->itemRepository->find($id);
	}

	public function removeItemFromCart(CartItem $item)
	{
		$this->removeEntity($item);
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
