<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Shipment;

interface ShipmentFormFactory
{

	/**
	 * @param Shipment|null $shipment
	 * @return ShipmentForm
	 */
	function create(Shipment $shipment = null);

}
