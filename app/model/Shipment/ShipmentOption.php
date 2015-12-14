<?php

namespace ShoPHP\Shipment;

interface ShipmentOption
{

	/**
	 * @return integer
	 */
	function getId();

	/**
	 * @return ShipmentType
	 */
	function getType();

}
