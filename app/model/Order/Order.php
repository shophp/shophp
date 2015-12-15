<?php

namespace ShoPHP\Order;

use ShoPHP\InvalidEnumException;

/**
 * @Entity
 * @Table(name="orders")
 */
class Order extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="order")
	 * @JoinColumn(nullable=false)
	 * @var Cart
	 */
	private $cart;

	public function __construct(Cart $cart)
	{
		if (count($cart->getItems()) === 0) {
			throw new InvalidEnumException('Cannot create order from empty cart.');
		}
		if (!$cart->hasShipment()) {
			throw new InvalidEnumException('Cannot create order from cart without shipment.');
		}

		$cart->setOrder($this);
		$this->cart = $cart;
	}

	public function getId()
	{
		return $this->id;
	}

}
