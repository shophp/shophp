<?php

namespace ShoPHP\Order;

use Nette\Http\Session;
use Nette\Http\SessionSection;

class CurrentCartService extends \Nette\Object
{

	/** @var CartService */
	private $cartService;

	/** @var SessionSection */
	private $cartSession;

	/** @var Cart */
	private $currentCart;

	public function __construct(CartService $cartService, Session $session)
	{
		$this->cartService = $cartService;
		$this->cartSession = $session->getSection('cart');
	}

	public function getCurrentCart()
	{
		if ($this->currentCart === null) {
			$currentCart = null;
			if ($this->cartSession->cartId !== null) {
				$currentCart = $this->cartService->getById($this->cartSession->cartId);
			}
			if ($currentCart === null) {
				$currentCart = new Cart();
			}

			$this->currentCart = $currentCart;
		}

		return $this->currentCart;
	}

	public function resetCurrentCart()
	{
		unset($this->cartSession->cartId);
		$this->currentCart = null;
	}

	public function saveCurrentCart()
	{
		$cart = $this->getCurrentCart();
		if (count($cart->getItems()) > 0) {
			if ($cart->getId() === null) {
				$this->cartService->create($cart);
				$this->cartSession->cartId = $cart->getId();
			} else {
				$this->cartService->update($cart);
			}
		}
	}

}
