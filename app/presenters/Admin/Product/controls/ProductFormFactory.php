<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product;

interface ProductFormFactory
{

	/**
	 * @param Product|null $editedProduct
	 * @return ProductForm
	 */
	function create(Product $editedProduct = null);

}
