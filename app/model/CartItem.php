<?php

namespace ShoPHP;

/**
 * @Entity
 * @Table(name="carts_items")
 */
class CartItem extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="items")
	 * @var Cart
	 */
	protected $cart;

	/**
	 * @OneToOne(targetEntity="Product")
     * @JoinColumn(name="product", referencedColumnName="id")
	 * @var Product
	 */
	protected $product;

	/** @Column(type="integer") * */
	protected $amount = 1;

	public function __construct(Cart $cart, Product $product, $amount = 1)
	{
		$this->cart = $cart;
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
