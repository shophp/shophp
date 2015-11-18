<?php

namespace ShoPHP\Admin\Product;

interface ProductFormFactory
{

	/**
	 * @param string $submitLabel
	 * @return ProductForm
	 */
	function create($submitLabel);

}
