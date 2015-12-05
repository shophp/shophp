<?php

namespace ShoPHP\Shipment;

/**
 * @Entity
 * @Table(name="shipment_personal_points")
 */
class ShipmentPersonalPoint extends \Nette\Object implements ShipmentType
{

	use ShipmentWithAddress;

	const ID = 1;

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	public function __construct($name, $street, $city, $zip)
	{
		$this->setAddress($name, $street, $city, $zip);
	}

	public function getId()
	{
		return $this->id;
	}

}
