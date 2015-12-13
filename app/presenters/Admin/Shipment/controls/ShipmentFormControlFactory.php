<?php

namespace ShoPHP\Admin\Shipment;

use ShoPHP\Shipment\ShipmentOption;

interface ShipmentFormControlFactory
{

	/**
	 * @param ShipmentOption|null $editedShipment
	 * @return ShipmentFormControl
	 */
	function create(ShipmentOption $editedShipment = null);

}
