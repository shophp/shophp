<?php

namespace ShoPHP;

/**
 * @Entity
 * @Table(
 *     name="carts_items",
 *     uniqueConstraints={@UniqueConstraint(name="cart_product", columns={"cart_id", "product"})}
 * )
 */
class CartItem extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Cart", inversedBy="items")
	 * @var Cart
	 */
	protected $cart;

	/**
	 * @ManyToOne(targetEntity="Product")
     * @JoinColumn(name="product", referencedColumnName="id")
	 * @var Product
	 */
	protected $product;

	/** @Column(type="integer") * */
	protected $amount = 1;

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

	public function setCart(Cart $cart)
	{
		$this->cart = $cart;
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

}
