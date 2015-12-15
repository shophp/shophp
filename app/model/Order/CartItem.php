<?php

namespace ShoPHP\Order;

use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Product\Product;

/**
 * @Entity
 * @Table(
 *     name="carts_items",
 *     uniqueConstraints={@UniqueConstraint(name="cart_product", columns={"cart_id", "product"})}
 * )
 */
class CartItem extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Order\Cart", inversedBy="items")
	 * @var Cart
	 */
	protected $cart;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Product\Product")
     * @JoinColumn(name="product", referencedColumnName="id")
	 * @var Product
	 */
	protected $product;

	/** @Column(type="integer") **/
	protected $amount = 1;

	/** @Column(type="float", nullable=true) **/
	protected $piecePrice = null;

	public function __construct(Product $product, $amount = 1)
	{
		$this->product = $product;
		$this->setAmount($amount);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getProduct()
	{
		return $this->product;
	}

	public function getCart()
	{
		return $this->cart;
	}

	public function setCart(Cart $cart)
	{
		$this->cart = $cart;
	}

	public function belongsIntoCart(Cart $cart)
	{
		return $this->getCart() === $cart;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($amount)
	{
		$amount = (int) $amount;
		if ($amount < 1) {
			throw new EntityInvalidArgumentException(sprintf('Invalid amount %d.', $amount));
		}
		$this->amount = $amount;
	}

	public function addAmount($amount)
	{
		$this->setAmount($this->getAmount() + $amount);
	}

	public function getPiecePrice()
	{
		if ($this->piecePrice !== null) {
			return $this->piecePrice;
		} else {
			return $this->getProduct()->getPrice();
		}
	}

	public function getPrice()
	{
		return $this->getAmount() * $this->getPiecePrice();
	}

	public function bakePrice()
	{
		if ($this->piecePrice !== null) {
			throw new EntityInvalidArgumentException('Price already baked.');
		}
		$this->piecePrice = $this->getPiecePrice();
	}

}
