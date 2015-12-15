<?php

namespace ShoPHP\Order;

use ShoPHP\Shipment\ShipmentOption;

interface Shipment
{

	/**
	 * @return integer
	 */
	function getId();

	/**
	 * @return Cart
	 */
	function getCart();

	/**
	 * @return ShipmentOption
	 */
	function getShipmentOption();

	/**
	 * @param Shipment $shipment
	 * @return bool
	 */
	function equals(self $shipment);

	/**
	 * @return float
	 */
	function getPrice();

	function bakePrice();

}
