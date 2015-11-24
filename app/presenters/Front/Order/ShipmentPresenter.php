<?php

namespace ShoPHP\Front\Order;

use ShoPHP\CartService;

class ShipmentPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var ShipmentFormFactory */
	private $shipmentFormFactory;

	/** @var CartService */
	private $cartService;

	public function __construct(ShipmentFormFactory $shipmentFormFactory, CartService $cartService)
	{
		parent::__construct();
		$this->shipmentFormFactory = $shipmentFormFactory;
		$this->cartService = $cartService;
	}

	public function actionDefault()
	{
		if (!$this->getCart()->hasItems()) {
			$this->flashMessage('Yout cart is empty.');
			$this->redirect(':Front:Home:Homepage:');
		}
	}

	public function createComponentShipmentForm()
	{
		$form = $this->shipmentFormFactory->create();
		$form->onSuccess[] = function(ShipmentForm $form) {
			$this->updateShipment($form);
		};
		return $form;
	}

	private function updateShipment(ShipmentForm $form)
	{
		if (!$form->hasErrors()) {
			$this->redirect(':Front:Order:Address:');
		}
	}

}
