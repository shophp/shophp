<?php

namespace ShoPHP\Admin\Category;

interface CategoriesFormFactory
{

	/**
	 * @param string $submitLabel
	 * @return CategoriesForm
	 */
	function create($submitLabel);

}
