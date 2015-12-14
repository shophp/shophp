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
		$form = $this->shipmentFormFactory->create($this->getCart()->getShipment());
		$form->onSuccess[] = function(ShipmentForm $form) {
			$this->updateShipment($form);
		};
		return $form;
	}

	private function updateShipment(ShipmentForm $form)
	{
		$values = $form->getValues();
		$shipmentOption = $form->getChosenShipment();
		$this->cartService->createShipmentForCart(
			$this->getCart(),
			$shipmentOption,
			$values->name,
			$values->street,
			$values->city,
			$values->zip
		);

		$this->redirect(':Front:Order:Payment:');
	}

}
