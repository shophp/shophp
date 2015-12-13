<?php

namespace ShoPHP\Admin\Shipment;

use ShoPHP\Shipment\ShipmentOption;

interface ShipmentFormFactory
{

	/**
	 * @param ShipmentOption|null $editedShipment
	 * @return ShipmentForm
	 */
	function create(ShipmentOption $editedShipment = null);

}
