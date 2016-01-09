<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="shipment_transport_companies")
 */
class ShipmentTransportCompany extends \Nette\Object implements ShipmentOption
{

	use ShipmentWithPrice;

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/** @Column(type="string") */
	protected $name;

	public function __construct($name, $price)
	{
		$this->setName($name);
		$this->setPrice($price);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$name = (string) $name;
		if ($name === '') {
			throw new EntityInvalidArgumentException('Name cannot be empty.');
		}
		$this->name = $name;
	}

	public function getType()
	{
		return ShipmentType::BY_TRANSPORT_COMPANY();
	}

}
