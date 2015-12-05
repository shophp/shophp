<?php

namespace ShoPHP\Front\Order;

use ShoPHP\CartService;

class CartPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var CartFormFactory */
	private $cartFormFactory;

	/** @var CartService */
	private $cartService;

	public function __construct(CartFormFactory $cartFormFactory, CartService $cartService)
	{
		parent::__construct();
		$this->cartFormFactory = $cartFormFactory;
		$this->cartService = $cartService;
	}

	public function actionDefault()
	{
	}

	public function createComponentCartForm()
	{
		$form = $this->cartFormFactory->create($this->getCart());
		$form->onSuccess[] = function(CartForm $form) {
			$this->recalculateCart($form);
		};
		return $form;
	}

	private function recalculateCart(CartForm $form)
	{
		$removeItemId = $form->getRemoveItemId();
		if ($removeItemId !== null) {
			$this->cartService->removeItemFromCart($this->cartService->getItemById($removeItemId));
		} else {
			$values = $form->getValues();
			foreach ($values->amount as $itemId => $amount) {
				$item = $this->cartService->getItemById($itemId);
				$item->setAmount($amount);
				$this->cartService->save($this->cartService->getCurrentCart());
			}
		}

		$this->flashMessage('Cart has been recalculated.');
		$this->redirect('this');
	}

}
