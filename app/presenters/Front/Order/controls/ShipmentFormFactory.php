<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Shipment;
use ShoPHP\User\User;

interface ShipmentFormFactory
{

	/**
	 * @param Shipment|null $shipment
	 * @return ShipmentForm
	 */
	function create(Shipment $shipment = null, User $user = null);

}
