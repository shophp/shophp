<?php

namespace ShoPHP\Admin\Category;

interface CategoriesFormControlFactory
{

	/**
	 * @param string $submitLabel
	 * @return CategoriesFormControl
	 */
	function create($submitLabel);

}
