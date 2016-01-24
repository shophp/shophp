<?php

namespace ShoPHP\Product;

use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(
 *     name="products_images",
 *     uniqueConstraints={@UniqueConstraint(name="products_images_path", columns={"path"})}
 * )
 */
class ProductImage extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/** @Column(type="string") */
	protected $path;

	/** @Column(type="string", nullable=true) */
	protected $description;

	/**
	 * @ManyToOne(targetEntity="\ShoPHP\Product\Product", inversedBy="images", cascade={"persist"})
	 * @var Product
	 */
	protected $product;

	/** @Column(type="integer", name="`order`") */
	protected $order;

	public function __construct(Product $product, $path)
	{
		$this->order = count($product->getImages()) + 1;
		$this->path = $path;
		$this->product = $product;
	}

	public function getId()
	{
		return $this->id;
	}

	public function hasDescription()
	{
		return $this->description !== null;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$description = $description !== null ? (string) $description : null;
		if ($description === '') {
			throw new EntityInvalidArgumentException('Description can be NULL but cannot be empty.');
		}
		$this->description = $description;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getProduct()
	{
		return $this->product;
	}

	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @param int $order
	 */
	public function setOrder($order)
	{
		$order = (int) $order;
		if ($order < 0) {
			throw new EntityInvalidArgumentException('Order must be positive number.');
		}

		$this->order = $order;
	}

}
