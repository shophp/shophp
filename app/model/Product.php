<?php

namespace ShoPHP;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="ShoPHP\Repository\ProductRepository")
 * @Table(name="products")
 */
class Product extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="string") * */
	protected $name;

	/** @Column(type="string") * */
	protected $description;

	/** @Column(type="float") * */
	protected $price;

	/** @Column(type="float") * */
	protected $discountPercent;

	/**
	 * @ManyToMany(targetEntity="Category", mappedBy="products")
	 * @var Category[]
	 */
	protected $categories;

	/**
	 * @param string $name
	 * @param float $price
	 */
	public function __construct($name, $price)
	{
		$this->setName($name);
		$this->setPrice($price);
		$this->categories = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$name = (string) $name;
		if ($name === '') {
			throw new EntityInvalidArgumentException('Name cannot be empty.');
		}
		$this->name = $name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param float $price
	 */
	public function setPrice($price)
	{
		$price = (float) $price;
		if ($price <= 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %f.', $price));
		}
		$this->price = $price;
	}

	public function getDiscountPercent()
	{
		return $this->discountPercent;
	}

	/**
	 * @param float $discountPercent
	 */
	public function setDiscountPercent($discountPercent)
	{
		$this->discountPercent = $discountPercent;
	}

	public function assignToCategory(Category $category)
	{
		$this->categories[] = $category;
	}
}

