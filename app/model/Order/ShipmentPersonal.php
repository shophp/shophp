<?php

namespace ShoPHP\Order;

use ShoPHP\Shipment\ShipmentPersonalPoint;

/**
 * @Entity
 * @Table(name="carts_shipment_personal")
 */
class ShipmentPersonal extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Shipment\ShipmentPersonalPoint")
	 * @var ShipmentPersonalPoint
	 */
	protected $personalPoint;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="shipmentPersonal")
	 * @var Cart
	 */
	protected $cart;

	public function __construct(ShipmentPersonalPoint $personalPoint)
	{
		$this->personalPoint = $personalPoint;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getPersonalPoint()
	{
		return $this->personalPoint;
	}

	public function getCart()
	{
		return $this->cart;
	}

}
