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
	 * @ManyToMany(targetEntity="Category", inversedBy="products")
	 * @JoinTable(
	 *     name="products_categories",
	 *     joinColumns={@JoinColumn(name="product", referencedColumnName="id")},
	 *     inverseJoinColumns={@JoinColumn(name="category", referencedColumnName="id")}
	 * )
	 * @var Categories|Category[]
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
		$this->categories = new Categories();
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

	public function getCategories()
	{
		if (!($this->categories instanceof Categories)) {
			$this->categories = new Categories(iterator_to_array($this->categories));
		}
		return $this->categories;
	}

	/**
	 * @param Categories|Category[] $categories
	 */
	public function setCategories(Categories $categories)
	{
		foreach ($categories as $category) {
			while ($category->hasParent()) {
				$category = $category->getParent();
				$categories[] = $category;
			}
		}
		$this->categories = new Categories();
		$idsAdded = [];
		foreach ($categories as $category) {
			if (isset($idsAdded[$category->getId()])) {
				continue;
			}
			$this->categories[] = $category;
			$idsAdded[$category->getId()] = true;
		}
	}
}

