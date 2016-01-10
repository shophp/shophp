<?php

namespace ShoPHP\Order;

use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\InvalidEnumException;

/**
 * @Entity
 * @Table(name="orders")
 */
class Order extends \Nette\Object
{

	const NUMBER_LENGTH = 7;

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/** @Column(type="string") */
	protected $number;

	/**
	 * @OneToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="order")
	 * @JoinColumn(nullable=false)
	 * @var Cart
	 */
	private $cart;

	public function __construct(Cart $cart, $number)
	{
		if (count($cart->getItems()) === 0) {
			throw new InvalidEnumException('Cannot create order from empty cart.');
		}
		if (strlen($number) !== self::NUMBER_LENGTH) {
			throw new EntityInvalidArgumentException(sprintf('Order number length must be exactly %d characters.', self::NUMBER_LENGTH));
		}

		$this->number = $number;
		$cart->setOrder($this);
		$this->cart = $cart;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function getCart()
	{
		return $this->cart;
	}

}
