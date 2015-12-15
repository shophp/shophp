<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CartService;
use ShoPHP\Order\CurrentCartService;

class ShipmentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var ShipmentFormFactory */
	private $shipmentFormFactory;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var CartService */
	private $cartService;

	public function __construct(
		ShipmentFormFactory $shipmentFormFactory,
		CurrentCartService $currentCartService,
		CartService $cartService
	)
	{
		parent::__construct();
		$this->shipmentFormFactory = $shipmentFormFactory;
		$this->currentCartService = $currentCartService;
		$this->cartService = $cartService;
	}

	public function actionDefault()
	{
	}

	public function createComponentShipmentForm()
	{
		$form = $this->shipmentFormFactory->create($this->currentCartService->getCurrentCart()->getShipment());
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
			$this->currentCartService->getCurrentCart(),
			$shipmentOption,
			$values->name,
			$values->street,
			$values->city,
			$values->zip
		);

		$this->currentCartService->saveCurrentCart();
		$this->redirect(':Front:Order:Payment:');
	}

}
