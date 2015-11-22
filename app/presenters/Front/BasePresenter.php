<?php

namespace ShoPHP\Front;

use ShoPHP\Category;
use ShoPHP\CategoryService;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(CategoryService $categoryService)
	{
		$this->categoryService = $categoryService;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryService->getRoot();
		$this->template->currentCategory = $this->currentCategory;
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

}
