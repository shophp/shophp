<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="shipment_transport_brands")
 */
class ShipmentTransportBrand extends \Nette\Object implements ShipmentOption
{

	use ShipmentWithAddress {
		getDescription as getBaseDescription;
	}
	use ShipmentFreeFromCertainOrderPrice;

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="float") */
	protected $price;

	public function __construct($name, $street, $city, $zip, $price)
	{
		$this->setAddress($name, $street, $city, $zip);

		$price = (float) $price;
		if ($price < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}
		$this->price = $price;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getDescription()
	{
		$description = $this->getBaseDescription();
		if ($this->getPrice() > 0) {
			$description = sprintf('%s (%s)', $description, $this->getPrice()); // todo format with currency
		}
		return $description;
	}

	public function getType()
	{
		return ShipmentType::TRANSPORT_TO_BRAND();
	}

}
