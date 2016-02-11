<?php

namespace ShoPHP\Front\Order;

use Nette\Security\User;
use ShoPHP\Order\CartService;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\Shipment\ShipmentService;

class ShipmentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var ShipmentFormFactory */
	private $shipmentFormFactory;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var CartService */
	private $cartService;

	/** @var ShipmentService */
	private $shipmentService;

	/** @var User */
	private $user;

	public function __construct(
		ShipmentFormFactory $shipmentFormFactory,
		CurrentCartService $currentCartService,
		CartService $cartService,
		ShipmentService $shipmentService,
		User $user
	)
	{
		parent::__construct();
		$this->shipmentFormFactory = $shipmentFormFactory;
		$this->currentCartService = $currentCartService;
		$this->cartService = $cartService;
		$this->shipmentService = $shipmentService;
		$this->user = $user;
	}

	public function actionDefault()
	{
		if (!$this->shipmentService->existsAnyShipmentOption()) {
			$this->redirect(':Front:Order:Payment:');
		}
	}

	public function createComponentShipmentForm()
	{
		$form = $this->shipmentFormFactory->create(
			$this->currentCartService->getCurrentCart()->getShipment(),
			$this->user->isLoggedIn() ? $this->user->getIdentity() : null
		);
		$form->onSuccess[] = function(ShipmentForm $form) {
			$this->updateShipment($form);
		};
		return $form;
	}

	private function updateShipment(ShipmentForm $form)
	{
		$values = $form->getValues();
		$shipmentOption = $form->getChosenShipment();

		if (isset($values->address)) {
			$this->cartService->createShipmentForCart(
				$this->currentCartService->getCurrentCart(),
				$shipmentOption,
				$values->address->name,
				$values->address->street,
				$values->address->city,
				$values->address->zip
			);
		} else {
			$this->cartService->createShipmentForCart(
				$this->currentCartService->getCurrentCart(),
				$shipmentOption
			);
		}

		$this->currentCartService->saveCurrentCart();
		$this->redirect(':Front:Order:Payment:');
	}

}
