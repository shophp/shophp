<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CurrentCartService;
use ShoPHP\Order\Order;
use ShoPHP\Order\OrderService;
use ShoPHP\Shipment\ShipmentService;

class PaymentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var OrderService */
	private $orderService;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var ShipmentService */
	private $shipmentService;

	public function __construct(
		OrderService $orderService,
		CurrentCartService $currentCartService,
		ShipmentService $shipmentService
	)
	{
		parent::__construct();
		$this->orderService = $orderService;
		$this->currentCartService = $currentCartService;
		$this->shipmentService = $shipmentService;
	}

	public function actionDefault()
	{
	}

	public function renderDefault()
	{
		$this->template->offersShipment = $this->shipmentService->existsAnyShipmentOption();
	}

	protected function createComponentOrderForm()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addSubmit('order', 'Order');
		$form->onSuccess[] = function (\Nette\Application\UI\Form $form) {
			$this->createOrder($form);
		};
		return $form;
	}

	private function createOrder(\Nette\Application\UI\Form $form)
	{
		$order = new Order($this->currentCartService->getCurrentCart());
		$this->orderService->create($order);
		$this->currentCartService->resetCurrentCart();
		$this->flashMessage('Ordered !');
		$this->redirect(':Front:Order:Order:');
	}

}
