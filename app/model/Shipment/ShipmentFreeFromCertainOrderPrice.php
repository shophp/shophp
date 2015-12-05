<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

trait ShipmentFreeFromCertainOrderPrice
{

	/** @Column(type="float", nullable=true) */
	protected $minimumOrderPriceToBeFree;

	public function isFreeFromCertainOrderPrice()
	{
		return $this->minimumOrderPriceToBeFree !== null;
	}

	public function getMinimumOrderPriceToBeFree()
	{
		return $this->minimumOrderPriceToBeFree;
	}

	public function setMinimumOrderPriceToBeFree($price)
	{
		$price = (float) $price;
		if ($price <= 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}
		$this->minimumOrderPriceToBeFree = $price;
	}

	public function eraseToBeFreeFromCertainOrderPrice()
	{
		$this->minimumOrderPriceToBeFree = null;
	}

}
