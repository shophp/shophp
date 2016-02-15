<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CurrentCartService;
use ShoPHP\Order\OrderService;
use ShoPHP\Payment\PaymentType;
use ShoPHP\Shipment\ShipmentService;

class PaymentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var OrderService */
	private $orderService;

	/** @var ShipmentService */
	private $shipmentService;

	/** @var PaymentFormFactory */
	private $paymentFormFactory;

	public function __construct(
		CurrentCartService $currentCartService,
		OrderService $orderService,
		ShipmentService $shipmentService,
		PaymentFormFactory $paymentFormFactory
	)
	{
		parent::__construct();
		$this->paymentFormFactory = $paymentFormFactory;
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

	protected function createComponentPaymentForm()
	{
		$form = $this->paymentFormFactory->create();
		$form->onSuccess[] = function (PaymentForm $form) {
			$this->createOrder($form);
		};
		return $form;
	}

	private function createOrder(PaymentForm $form)
	{
		$values = $form->getValues();
		$this->orderService->createFromCart(
			$this->currentCartService->getCurrentCart(),
			PaymentType::createFromValue($values->paymentType)
		);
		$this->currentCartService->resetCurrentCart();
		$this->redirect(':Front:Order:Order:');
	}

}
