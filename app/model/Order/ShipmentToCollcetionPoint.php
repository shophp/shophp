<?php

namespace ShoPHP\Order;

use ShoPHP\Shipment\ShipmentCollectionPoint;

/**
 * @Entity
 * @Table(name="carts_shipment_to_collection_point")
 */
class ShipmentToCollectionPoint extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Shipment\ShipmentCollectionPoint")
	 * @var ShipmentCollectionPoint
	 */
	protected $collectionPoint;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="shipmentToCollectionPoint")
	 * @var Cart
	 */
	protected $cart;

	public function __construct(ShipmentCollectionPoint $collectionPoint)
	{
		$this->collectionPoint = $collectionPoint;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getCollectionPoint()
	{
		return $this->collectionPoint;
	}

	public function getCart()
	{
		return $this->cart;
	}

}
