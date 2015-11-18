<?php

namespace ShoPHP\Front;

use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(CategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryRepository->getRoot();
		$this->template->currentCategory = $this->currentCategory;
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

}
