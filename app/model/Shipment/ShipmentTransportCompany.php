<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="shipment_transport_companies")
 */
class ShipmentTransportCompany extends \Nette\Object implements ShipmentType
{

	use ShipmentFreeFromCertainOrderPrice;

	const ID = 2;

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="string") */
	protected $name;

	/** @Column(type="float") */
	protected $price;

	public function getId()
	{
		return $this->id;
	}

	public function __construct($name, $price)
	{
		$name = (string) $name;

		if ($name === '') {
			throw new EntityInvalidArgumentException('Name cannot be empty.');
		}

		$price = (float) $price;
		if ($price < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}

		$this->name = $name;
		$this->price = $price;
	}

	public function getPrice()
	{
		return $this->price;
	}

}
