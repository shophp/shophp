<?php

namespace ShoPHP\Front\Order;

class ShipmentForm extends \Nette\Application\UI\Form
{

	public function __construct()
	{
		parent::__construct();
		$this->createFields();
	}

	private function createFields()
	{
		$this->addShipmentControl();
		$this->addSubmit('next', 'Next');
	}

	private function addShipmentControl()
	{
		$this->addSelect('shipment', 'Shipment', [
			'Personally',
			'DPD',
		]);
	}

}
