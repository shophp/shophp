<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product\Product;

interface ProductFormControlFactory
{

	/**
	 * @param Product|null $editedProduct
	 * @return ProductFormControl
	 */
	function create(Product $editedProduct = null);

}
