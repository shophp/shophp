<?php

namespace ShoPHP;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="carts")
 */
class Cart extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @OneToMany(targetEntity="CartItem", mappedBy="cart", cascade={"persist"})
	 * @var CartItem[]
	 */
	protected $items;

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

}

