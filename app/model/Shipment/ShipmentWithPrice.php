<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

trait ShipmentWithPrice
{

	/** @Column(type="float") */
	protected $price;

	/** @Column(type="float", nullable=true) */
	protected $minimumOrderPriceToBeFree;

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price)
	{
		$price = (float) $price;
		if ($price < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}
		$this->price = $price;
	}

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
