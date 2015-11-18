<?php

namespace ShoPHP\Repository;

use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\Category;
use ShoPHP\Product;

class RepositoryFactory extends \Nette\Object
{

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return CategoryRepository
	 */
	public function getCategoryRepository()
	{
		return $this->entityManager->getRepository(Category::class);
	}

	/**
	 * @return ProductRepository
	 */
	public function getProductRepository()
	{
		return $this->entityManager->getRepository(Product::class);
	}

}
