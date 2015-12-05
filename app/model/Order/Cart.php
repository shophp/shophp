<?php

namespace ShoPHP\Order;

use Doctrine\Common\Collections\ArrayCollection;
use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentTransportBrand;
use ShoPHP\Shipment\ShipmentTransportCompany;

/**
 * @Entity
 * @Table(name="carts")
 */
class Cart extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @OneToMany(targetEntity="\ShoPHP\Order\CartItem", mappedBy="cart", cascade={"persist"})
	 * @var CartItem[]
	 */
	protected $items;

	/** @Column(type="integer") */
	protected $shipmentType;

	/** @Column(type="string", nullable=true) */
	protected $name;

	/** @Column(type="string", nullable=true) */
	protected $street;

	/** @Column(type="string", nullable=true) */
	protected $city;

	/** @Column(type="string", nullable=true) */
	protected $zip;

	public function __construct()
	{
		$this->items = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function hasItems()
	{
		return count($this->getItems()) > 0;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function addItem(CartItem $item)
	{
		foreach ($this->items as $addedItem) {
			if ($addedItem->getProduct() === $item->getProduct()) {
				$addedItem->addAmount($item->getAmount());
				return;
			}
		}
		$item->setCart($this);
		$this->items[] = $item;
	}

	public function getPrice()
	{
		$price = 0;
		foreach ($this->getItems() as $item) {
			$price += $item->getPrice();
		}
		return $price;
	}

	public function isForPersonalPickup()
	{
		return $this->shipmentType === ShipmentPersonalPoint::ID;
	}

	public function isShippedByTransportCompany()
	{
		return $this->shipmentType === ShipmentTransportCompany::ID;
	}

	public function isShippedToBrand()
	{
		return $this->shipmentType === ShipmentTransportBrand::ID;
	}

	public function setShipmentType($shipmentType)
	{
		$shipmentTypes = [
			ShipmentPersonalPoint::ID,
			ShipmentTransportCompany::ID,
			ShipmentTransportBrand::ID,
		];
		if (!in_array($shipmentType, $shipmentTypes, true)) {
			throw new EntityInvalidArgumentException(sprintf('Invalid shipment type %d.', $shipmentType));
		}

		$this->shipmentType = $shipmentType;
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
