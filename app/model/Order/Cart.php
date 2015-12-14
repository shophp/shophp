<?php

namespace ShoPHP\Order;

use Doctrine\Common\Collections\ArrayCollection;
use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Shipment\ShipmentType;

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

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\ShipmentPersonal", mappedBy="cart")
	 * @var ShipmentPersonal
	 */
	protected $shipmentPersonal;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\ShipmentByTransportCompany", mappedBy="cart")
	 * @var ShipmentByTransportCompany
	 */
	protected $shipmentByTransportCompany;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\ShipmentToCollectionPoint", mappedBy="cart")
	 * @var ShipmentToCollectionPoint
	 */
	protected $shipmentToCollectionPoint;

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

	public function getShipmentType()
	{
		return ShipmentType::createFromValue($this->shipmentType);
	}

	public function setShipmentType(ShipmentType $shipmentType)
	{
		$this->shipmentType = $shipmentType->getValue();
	}

}
