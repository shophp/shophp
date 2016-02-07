<?php

namespace ShoPHP;

use ShoPHP\Shipment\ShipmentHelper;
use ShoPHP\Shipment\ShipmentOption;

class TemplateFilters extends \Nette\Object
{

	/** @var MoneyHelper */
	private $moneyHelper;

	/** @var ShipmentHelper */
	private $shipmentHelper;

	public function __construct(MoneyHelper $moneyHelper, ShipmentHelper $shipmentHelper)
	{
		$this->moneyHelper = $moneyHelper;
		$this->shipmentHelper = $shipmentHelper;
	}

	public function formatPrice($price)
	{
		return $this->moneyHelper->formatPrice($price);
	}

	public function formatShipmentOption(ShipmentOption $option)
	{
		return $this->shipmentHelper->formatShipmentOption($option);
	}

}
