<?php

namespace ShoPHP\Repository;

use ShoPHP\EntityDuplicateException;
use ShoPHP\Product;

class ProductRepository extends \ShoPHP\Repository
{

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
		return $this->find($id);
	}

	/**
	 * @param string $path
	 * @return Product[]
	 */
	public function findByPath($path)
	{
		return $this->findBy([
			'path' => $path,
		]);
	}

	private function checkDuplicity(Product $product)
	{
		foreach ($product->getCategories() as $category) {
			foreach ($category->getProducts() as $duplicateCandidate) {
				if ($duplicateCandidate !== $product && $duplicateCandidate->getPath($category) === $product->getPath($category)) {
					throw new EntityDuplicateException(sprintf('Product with name %s already exists.', $product->getName()));
				}
			}
		}
	}

}
