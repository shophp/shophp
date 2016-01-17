<?php

namespace ShoPHP\Order;

use Doctrine\Common\Collections\ArrayCollection;
use ShoPHP\EntityImmutableException;
use ShoPHP\Shipment\ShipmentType;
use ShoPHP\User\User;

/**
 * @Entity
 * @Table(name="carts")
 */
class Cart extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/**
	 * @OneToMany(targetEntity="\ShoPHP\Order\CartItem", mappedBy="cart", cascade={"persist"})
	 * @var CartItem[]
	 */
	protected $items;

	/** @Column(type="integer", nullable=true) */
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

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Order", mappedBy="cart")
	 * @var Order|null
	 */
	protected $order;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\User\User", inversedBy="carts")
	 * @var User|null
	 */
	protected $user;

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

	public function getItemsAmount()
	{
		$amount = 0;
		foreach ($this->getItems() as $item) {
			$amount += $item->getAmount();
		}
		return $amount;
	}

	public function addItem(CartItem $item)
	{
		$this->checkMutability();

		foreach ($this->items as $addedItem) {
			if ($addedItem->getProduct() === $item->getProduct()) {
				$addedItem->addAmount($item->getAmount());
				return;
			}
		}
		$item->setCart($this);
		$this->items[] = $item;
	}

	public function getItemsPrice()
	{
		$price = 0.0;
		foreach ($this->getItems() as $item) {
			$price += $item->getPrice();
		}
		return $price;
	}

	public function hasShipment()
	{
		return $this->shipmentType !== null;
	}

	/**
	 * @return ShipmentType|null
	 */
	public function getShipmentType()
	{
		if ($this->shipmentType !== null) {
			return ShipmentType::createFromValue($this->shipmentType);
		}
		return null;
	}

	public function getPrice()
	{
		$price = $this->getItemsPrice();
		if ($this->hasShipment()) {
			$price += $this->getShipment()->getPrice();
		}
		return $price;
	}

	/**
	 * @return Shipment|null
	 */
	public function getShipment()
	{
		if ($this->getShipmentType() !== null) {
			if ($this->getShipmentType()->isPersonal()) {
				return $this->shipmentPersonal;
			} elseif ($this->getShipmentType()->isByTransportCompany()) {
				return $this->shipmentByTransportCompany;
			} elseif ($this->getShipmentType()->isToCollectionPoint()) {
				return $this->shipmentToCollectionPoint;
			}
		}
		return null;
	}

	public function setShipment(Shipment $shipment)
	{
		$this->checkMutability();

		$this->shipmentPersonal = null;
		$this->shipmentByTransportCompany = null;
		$this->shipmentToCollectionPoint = null;

		$type = $shipment->getShipmentOption()->getType();
		$this->shipmentType = $type->getValue();

		if ($type->isPersonal()) {
			$this->shipmentPersonal = $shipment;
		} elseif ($type->isByTransportCompany()) {
			$this->shipmentByTransportCompany = $shipment;
		} elseif ($type->isToCollectionPoint()) {
			$this->shipmentToCollectionPoint = $shipment;
		}
	}

	public function hasUser()
	{
		return $this->getUser() !== null;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser(User $user)
	{
		$this->checkMutability();

		$this->user = $user;
	}

	public function isOrdered()
	{
		return $this->getOrder() !== null;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function setOrder(Order $order)
	{
		$this->checkMutability();

		foreach ($this->getItems() as $item) {
			$item->bakePrice();
		}
		if ($this->hasShipment()) {
			$this->getShipment()->bakePrice();
		}
		$this->order = $order;
	}

	private function checkMutability()
	{
		if ($this->isOrdered()) {
			throw new EntityImmutableException('Ordered cart cannot be modified.');
		}
	}

}
