<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CartService;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\Shipment\ShipmentService;

class CartPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var CartFormFactory */
	private $cartFormFactory;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var CartService */
	private $cartService;

	/** @var ShipmentService */
	private $shipmentService;

	public function __construct(
		CartFormFactory $cartFormFactory,
		CurrentCartService $currentCartService,
		CartService $cartService,
		ShipmentService $shipmentService
	)
	{
		parent::__construct();
		$this->cartFormFactory = $cartFormFactory;
		$this->currentCartService = $currentCartService;
		$this->cartService = $cartService;
		$this->shipmentService = $shipmentService;
	}

	public function actionDefault()
	{
	}

	public function renderDefault()
	{
		$this->template->offersShipment = $this->shipmentService->existsAnyShipmentOption();
	}

	public function createComponentCartForm()
	{
		$form = $this->cartFormFactory->create($this->currentCartService->getCurrentCart());
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
				$this->currentCartService->saveCurrentCart();
			}
		}

		$this->flashMessage('Cart has been recalculated.');
		$this->redirect('this');
	}

}
