<?php

namespace ShoPHP\Shipment;

use ShoPHP\MoneyHelper;

class ShipmentHelper extends \Nette\Object
{

	/** @var MoneyHelper */
	private $moneyHelper;

	public function __construct(MoneyHelper $moneyHelper)
	{
		$this->moneyHelper = $moneyHelper;
	}

	public function formatShipmentOption(ShipmentOption $shipment)
	{
		if ($shipment instanceof ShipmentPersonalPoint) {
			$description = $this->getAddressDescription($shipment);

		} elseif ($shipment instanceof ShipmentCollectionPoint) {
			$description = $this->addPriceDescription($shipment, $this->getAddressDescription($shipment));

		} elseif ($shipment instanceof ShipmentTransportCompany) {
			$description = $this->addPriceDescription($shipment, $shipment->getName());

		} else {
			throw new \LogicException();
		}

		return $description;
	}

	/**
	 * @param ShipmentCollectionPoint|ShipmentPersonalPoint $shipment
	 */
	private function getAddressDescription(ShipmentOption $shipment)
	{
		$description = sprintf('%s %s %s', $shipment->getStreet(),  $shipment->getCity(), $shipment->getZip());
		if ($shipment->hasName()) {
			$description = sprintf('%s, %s', $shipment->getName(), $description);
		}
		return $description;
	}

	/**
	 * @param ShipmentCollectionPoint|ShipmentTransportCompany $shipment
	 */
	private function addPriceDescription(ShipmentOption $shipment, $description)
	{
		if ($shipment->getPrice() > 0) {
			$description = sprintf('%s (%s)', $description, $this->moneyHelper->formatPrice($shipment->getPrice()));
		}
		return $description;
	}

}
