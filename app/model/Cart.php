<?php

namespace ShoPHP;

use Doctrine\Common\Collections\ArrayCollection;
use Nette\Utils\Strings;

/**
 * @Entity
 * @Table(name="carts")
 */
class Cart extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/**
	 * @OneToMany(targetEntity="CartItem", mappedBy="cart")
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
		$this->items[] = $item;
	}

}

