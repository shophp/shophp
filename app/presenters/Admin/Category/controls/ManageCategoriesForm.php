<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Product\CategoryService;

class ManageCategoriesForm extends \Nette\Application\UI\Form
{

	/** @var CategoryService */
	private $categoryService;

	public function __construct(CategoryService $categoryService)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->createFields();
	}

	protected function createFields()
	{
		$this->addDeleteContainer();
	}

	private function addDeleteContainer()
	{
		$deleteContainer = $this->addContainer('delete');
		foreach ($this->categoryService->getAll() as $category) {
			if (!$category->hasProducts()) {
				$deleteContainer->addSubmit($category->getId(), 'x');
			}
		}
	}

}
