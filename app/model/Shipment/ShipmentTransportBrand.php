<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="shipment_transport_brands")
 */
class ShipmentTransportBrand extends \Nette\Object implements ShipmentType
{

	use ShipmentWithAddress;
	use ShipmentFreeFromCertainOrderPrice;

	const ID = 3;

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="float") */
	protected $price;

	public function getId()
	{
		return $this->id;
	}

	public function __construct($name, $street, $city, $zip, $price)
	{
		$this->setAddress($name, $street, $city, $zip);

		$price = (float) $price;
		if ($price < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}
		$this->price = $price;
	}

}
