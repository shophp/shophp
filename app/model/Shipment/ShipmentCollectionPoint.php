<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="shipment_collection_points")
 */
class ShipmentCollectionPoint extends \Nette\Object implements ShipmentOption
{

	use ShipmentWithAddress;
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

	public function getType()
	{
		return ShipmentType::TO_COLLECTION_POINT();
	}

}
