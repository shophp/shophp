<?php

namespace ShoPHP\Order;

use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Shipment\ShipmentTransportCompany;

/**
 * @Entity
 * @Table(name="carts_shipment_by_transport_company")
 */
class ShipmentByTransportCompany extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Shipment\ShipmentTransportCompany")
	 * @var ShipmentTransportCompany
	 */
	protected $transportCompany;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="shipmentByTransportCompany")
	 * @var Cart
	 */
	protected $cart;

	/** @Column(type="string") */
	protected $name;

	/** @Column(type="string") */
	protected $street;

	/** @Column(type="string") */
	protected $city;

	/** @Column(type="string") */
	protected $zip;

	public function __construct(ShipmentTransportCompany $transportCompany, $name, $street, $city, $zip)
	{
		$this->transportCompany = $transportCompany;
		$this->setAddress($name, $street, $city, $zip);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTransportCompany()
	{
		return $this->transportCompany;
	}

	public function getCart()
	{
		return $this->cart;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function getZip()
	{
		return $this->zip;
	}

	public function setAddress($name, $street, $city, $zip)
	{
		$name = (string) $name;
		$street = (string) $street;
		$city = (string) $city;
		$zip = (string) $zip;

		if ($name === '') {
			throw new EntityInvalidArgumentException('Name cannot be empty.');
		}
		if ($street === '') {
			throw new EntityInvalidArgumentException('Street cannot be empty.');
		}
		if ($city === '') {
			throw new EntityInvalidArgumentException('City cannot be empty.');
		}
		if ($zip === '') {
			throw new EntityInvalidArgumentException('Zip cannot be empty.');
		}

		$this->name = $name;
		$this->street = $street;
		$this->city = $city;
		$this->zip = $zip;
	}

}
