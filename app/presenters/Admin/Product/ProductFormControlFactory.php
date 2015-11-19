<?php

namespace ShoPHP\Admin\Product;

interface ProductFormControlFactory
{

	/**
	 * @param string $submitLabel
	 * @return ProductFormControl
	 */
	function create($submitLabel);

}
