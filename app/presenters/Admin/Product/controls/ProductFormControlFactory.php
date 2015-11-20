<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product;

interface ProductFormControlFactory
{

	/**
	 * @param Product|null $editedProduct
	 * @return ProductFormControl
	 */
	function create(Product $editedProduct = null);

}
