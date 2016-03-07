<?php

namespace ShoPHP\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Nette\Utils\Strings;
use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="products")
 */
class Product extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/** @Column(type="string") */
	protected $name;

	/** @Column(type="string") */
	protected $path;

	/** @Column(type="string") */
	protected $description;

	/** @Column(type="float") */
	protected $price;

	/** @Column(type="float") */
	protected $discount = 0;

	/**
	 * @ManyToMany(targetEntity="\ShoPHP\Product\Category", inversedBy="products")
	 * @JoinTable(
	 *     name="products_categories",
	 *     joinColumns={@JoinColumn(name="product", referencedColumnName="id", onDelete="CASCADE")},
	 *     inverseJoinColumns={@JoinColumn(name="category", referencedColumnName="id")}
	 * )
	 * @var Categories|Category[]
	 */
	protected $categories;

	/**
	 * @OneToMany(targetEntity="\ShoPHP\Product\ProductImage", mappedBy="product")
	 * @var ProductImage[]
	 */
	protected $images;

	/**
	 * @param string $name
	 * @param float $price
	 */
	public function __construct($name, $price)
	{
		$this->setName($name);
		$this->setOriginalPrice($price);
		$this->categories = new Categories();
		$this->images = new ArrayCollection();
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
		$this->path = Strings::webalize($name);
	}

	public function getPath(Category $fromCategory = null)
	{
		if ($fromCategory !== null && count($this->getCategories()) === 0) {
			throw new EntityInvalidArgumentException(sprintf('Product %s does not belong into any category.', $this->getName()));

		} elseif ($fromCategory !== null && !$this->belongsIntoCategory($fromCategory)) {
			throw new EntityInvalidArgumentException(sprintf(
				'Product %s does not belong into category %s.',
				$this->getName(),
				$fromCategory->getName()
			));

		} elseif ($fromCategory === null) {
			$fromCategory = $this->getMainCategory();
		}

		if ($fromCategory === null) {
			return $this->path;
		} else {
			return sprintf('%s/%s', $fromCategory->getPath(), $this->path);
		}
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
		return $this->price - $this->discount;
	}

	public function getOriginalPrice()
	{
		return $this->price;
	}

	/**
	 * @param float $price
	 */
	public function setOriginalPrice($price)
	{
		$price = (float) $price;
		if ($price <= 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid price %.2f.', $price));
		}
		$this->price = $price;
	}

	public function hasDiscount()
	{
		return $this->discount !== 0.0;
	}

	public function getNominalDiscount()
	{
		return $this->discount;
	}

	/**
	 * @return float
	 */
	public function getDiscountPercent()
	{
		return ($this->discount / $this->price) * 100;
	}

	/**
	 * @param float $discount
	 */
	public function setDiscountPercent($discount)
	{
		$discount = (float) $discount;
		if ($discount < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid percent discount %f.', $discount));
		}

		$this->discount = $this->price * ($discount / 100);
	}

	/**
	 * @param float $discount
	 */
	public function setNominalDiscount($discount)
	{
		$discount = (float) $discount;
		if ($discount < 0) {
			throw new EntityInvalidArgumentException(sprintf('Invalid nominal discount %f.', $discount));
		}

		$this->discount = $discount;
	}

	public function getCategories()
	{
		if (!($this->categories instanceof Categories)) {
			$this->categories = new Categories(iterator_to_array($this->categories));
		}
		return $this->categories;
	}

	public function getMainCategory()
	{
		return count($this->categories) > 0 ? $this->categories[0] : null;
	}

	public function belongsIntoCategory(Category $category)
	{
		foreach ($this->getCategories() as $categoryCandidate) {
			if ($category === $categoryCandidate) {
				return true;
			}
		}
		return false;
	}

	public function isDirectlyInCategory(Category $category)
	{
		foreach ($category->getSubcategories() as $subcategory) {
			if ($this->belongsIntoCategory($subcategory)) {
				return false;
			}
		}
		return true;
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

	public function hasImages()
	{
		return count($this->images) > 0;
	}

	public function getImages()
	{
		static $sorted = false;
		if (!$sorted) {
			$images = $this->images->toArray();
			usort($images, function(ProductImage $imageA, ProductImage $imageB) {
				return $imageA->getOrder() === $imageB->getOrder() ? 0 : ($imageA->getOrder() > $imageB->getOrder() ? 1 : -1);
			});
			$this->images = new ArrayCollection($images);
			$sorted = true;
		}

		return $this->images;
	}

	public function getMainImage()
	{
		if ($this->hasImages()) {
			$images = $this->getImages();
			return $images[0];
		}
		return null;
	}

	public function addImage(ProductImage $image)
	{
		$this->images[] = $image;
	}

}
