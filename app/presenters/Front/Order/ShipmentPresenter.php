<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CartService;

class ShipmentPresenter extends \ShoPHP\Front\Order\BasePresenter
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
		$shipmentOption = $form->getChosenShipment();

		if (!$form->hasErrors()) {
			$this->redirect(':Front:Order:Address:');
		}
	}

}
