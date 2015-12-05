<?php

namespace ShoPHP\Product;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\EntityDuplicateException;

class ProductService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(Product::class);
	}

	public function create(Product $product)
	{
		$this->checkDuplicity($product);
		$this->createEntity($product);
	}

	public function update(Product $product)
	{
		$this->checkDuplicity($product);
		$this->updateEntity($product);
	}

	/**
	 * @param integer $id
	 * @return Product|null
	 */
	public function getById($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @param string $path
	 * @return Product[]
	 */
	public function getByPath($path)
	{
		return $this->repository->findBy([
			'path' => $path,
		]);
	}

	private function checkDuplicity(Product $product)
	{
		foreach ($product->getCategories() as $category) {
			foreach ($category->getProducts() as $duplicateCandidate) {
				$path = $product->getPath($category);
				if ($duplicateCandidate !== $product && $duplicateCandidate->getPath($category) === $path) {
					throw new EntityDuplicateException(sprintf('Product with path %s already exists.', $path));
				}
			}
		}
	}

}
