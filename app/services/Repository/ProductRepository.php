<?php

namespace ShoPHP\Repository;

use ShoPHP\Product;

class ProductRepository extends \ShoPHP\Repository
{

	public function create(Product $product)
	{
		$this->createEntity($product);
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

}
