<?php

namespace ShoPHP\Front;

use ShoPHP\Order\Cart;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(
		CategoryService $categoryService,
		CurrentCartService $currentCartService
	)
	{
		$this->categoryService = $categoryService;
		$this->currentCartService = $currentCartService;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryService->getRoot();
		$this->template->currentCategory = $this->currentCategory;
		$this->template->cart = $this->currentCartService->getCurrentCart();
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

}
