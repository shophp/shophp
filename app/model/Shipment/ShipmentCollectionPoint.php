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
	use ShipmentWithPrice;

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	public function __construct($name, $street, $city, $zip, $price)
	{
		$this->setAddress($name, $street, $city, $zip);
		$this->setPrice($price);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getType()
	{
		return ShipmentType::TO_COLLECTION_POINT();
	}

}
