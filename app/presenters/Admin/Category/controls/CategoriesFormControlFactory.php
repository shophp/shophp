<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Product\Category;

interface CategoriesFormControlFactory
{

	/**
	 * @param Category $editedCategory
	 * @return CategoriesFormControl
	 */
	function create(Category $editedCategory = null);

}
