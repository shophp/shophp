<?php

namespace ShoPHP\Order;

use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Shipment\ShipmentTransportCompany;

/**
 * @Entity
 * @Table(name="carts_shipment_by_transport_company")
 */
class ShipmentByTransportCompany extends \Nette\Object implements Shipment
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

	/** @Column(type="float", nullable=true) */
	protected $price = null;

	public function __construct(Cart $cart, ShipmentTransportCompany $transportCompany, $name, $street, $city, $zip)
	{
		$this->cart = $cart;
		$this->transportCompany = $transportCompany;
		$this->setAddress($name, $street, $city, $zip);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getShipmentOption()
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

	/**
	 * @param Shipment|self $shipment
	 */
	public function equals(Shipment $shipment)
	{
		return $shipment->getShipmentOption() === $this->getShipmentOption()
			&& $shipment->getName() === $this->getName()
			&& $shipment->getStreet() === $this->getStreet()
			&& $shipment->getCity() === $this->getCity()
			&& $shipment->getZip() === $this->getZip();
	}

	public function getPrice()
	{
		if ($this->price !== null) {
			return $this->price;
		} else {
			return $this->getShipmentOption()->getPrice();
		}
	}

	public function bakePrice()
	{
		if ($this->price !== null) {
			throw new EntityInvalidArgumentException('Price already baked.');
		}
		$this->price = $this->getPrice();
	}

	private function setAddress($name, $street, $city, $zip)
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
