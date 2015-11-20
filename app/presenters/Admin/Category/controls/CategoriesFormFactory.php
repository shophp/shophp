<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Category;

interface CategoriesFormFactory
{

	/**
	 * @param Category|null $editedCategory
	 * @return CategoriesForm
	 */
	function create(Category $editedCategory = null);

}
