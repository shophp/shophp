<?php

namespace ShoPHP\Repository;

use ShoPHP\Product;

class ProductRepository extends \ShoPHP\Repository
{

	public function create(Product $product)
	{
		$this->createEntity($product);
	}

}
