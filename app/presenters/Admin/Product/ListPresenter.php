<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product\CategoryService;

class ListPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	public function __construct(CategoryService $categoryService)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
	}

	public function actionDefault()
	{
	}

	public function renderDefault()
	{
		$this->template->categories = $this->categoryService->getRoot();
	}

}
