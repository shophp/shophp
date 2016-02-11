<?php

namespace ShoPHP\Order;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use ShoPHP\User\User;

class CurrentCartService extends \Nette\Object
{

	/** @var CartService */
	private $cartService;

	/** @var SessionSection */
	private $cartSession;

	/** @var Cart */
	private $currentCart;

	/** @var \Nette\Security\User */
	private $user;

	public function __construct(CartService $cartService, Session $session, \Nette\Security\User $user)
	{
		$this->cartService = $cartService;
		$this->cartSession = $session->getSection('cart');
		$this->user = $user;
	}

	public function getCurrentCart()
	{
		if ($this->currentCart === null) {
			$currentCart = null;
			if ($this->cartSession->cartId !== null) {
				$currentCart = $this->cartService->getById($this->cartSession->cartId);
			}
			if ($currentCart === null) {
				/** @var User|null $identity */
				$identity = $this->user->getIdentity();
				$currentCart = new Cart($identity);
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
		if ($cart->hasItems()) {
			if ($cart->getId() === null) {
				$this->cartService->create($cart);
				$this->cartSession->cartId = $cart->getId();
			} else {
				$this->cartService->update($cart);
			}
		}
	}

	public function consolidateCurrentCartWithCurrentUser()
	{
		if ($this->user->isLoggedIn()) {
			/** @var User $identity */
			$identity = $this->user->getIdentity();
			if ($this->getCurrentCart()->hasItems()) {
				$this->getCurrentCart()->setUser($identity);
				$this->saveCurrentCart();

			} elseif ($identity->hasAnyCart()) {
				$this->setCurrentCart($identity->getLastCart());
			}
		}
	}

	private function setCurrentCart(Cart $cart)
	{
		$this->currentCart = $cart;
		$this->cartSession->cartId = $cart->getId();
	}

}
