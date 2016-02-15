<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\OrderService;
use ShoPHP\Payment\PaymentType;

class PaymentForm extends \Nette\Application\UI\Form
{

	/** @var OrderService */
	private $orderService;

	public function __construct(OrderService $orderService)
	{
		parent::__construct();
		$this->orderService = $orderService;

		$this->createFields();
	}

	private function createFields()
	{
		$this->addTypeControl();
		$this->addSubmit('order', 'Create order');
	}

	private function addTypeControl()
	{
		$paymentItems = [];
		foreach (PaymentType::getValues() as $paymentType) {
			$paymentType = PaymentType::createFromValue($paymentType);
			if ($this->orderService->isPaymentTypeAvailable($paymentType)) {
				$paymentItems[$paymentType->getValue()] = $paymentType->getLabel();
			}
		}

		if (count($paymentItems) === 0) {
			throw new \Exception('No payment type available');
		}

		$this->addRadioList('paymentType', 'Payment', $paymentItems)
			->setRequired('Please choose payment type.')
			->setDefaultValue(PaymentType::CASH);
	}

}
