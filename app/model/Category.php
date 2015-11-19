<?php

namespace ShoPHP;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="ShoPHP\Repository\CategoryRepository")
 * @Table(
 *     name="categories",
 *     uniqueConstraints={@UniqueConstraint(name="categories_path", columns={"path"})}
 * )
 */
class Category extends \Nette\Object
{

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Category", inversedBy="subcategories")
	 * @var self
	 */
	protected $parent;

	/**
	 * @OneToMany(targetEntity="Category", mappedBy="parent")
	 * @var Categories|self[]
	 */
	protected $subcategories;

	/** @Column(type="string") **/
	protected $name;

	/** @Column(type="string") **/
	protected $path;

	/**
	 * @ManyToMany(targetEntity="Product", mappedBy="categories")
	 * @var Product[]
	 */
	protected $products;

	public function __construct()
	{
		$this->subcategories = new Categories();
		$this->products = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function hasSubcategories()
	{
		return count($this->getSubcategories()) > 0;
	}

	public function getSubcategories()
	{
		return $this->subcategories;
	}

	public function isSubcategoryOf(self $category)
	{
		return $this->getParent()->isSelfOrSubcategoryOf($category);
	}

	public function isSelfOrSubcategoryOf(self $category)
	{
		if ($this === $category) {
			return true;
		}
		if ($this->getParent() === null) {
			return false;
		}
		return $this->getParent()->isSelfOrSubcategoryOf($category);
	}

	public function getProducts()
	{
		return $this->products;
	}

	public function hasProducts()
	{
		return count($this->products) > 0;
	}

}
