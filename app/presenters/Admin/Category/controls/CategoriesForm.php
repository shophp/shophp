<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;

class CategoriesForm extends \Nette\Application\UI\Form
{

	const ROOT_CATEGORY_KEY = 'root';

	/** @var CategoryService */
	private $categoryService;

	/** @var Category|null */
	private $editedCategory;

	public function __construct(CategoryService $categoryService, Category $editedCategory = null)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->editedCategory = $editedCategory;
		$this->createFields();
	}

	private function createFields()
	{
		$this->addNameControl();
		$this->addParentCategoryControl();
		$this->addSubmit('send', $this->editedCategory === null ? 'Create' : 'Update');
	}

	private function addNameControl()
	{
		$nameControl = $this->addText('name', 'Name');
		$nameControl->setRequired();
		if ($this->editedCategory !== null) {
			$nameControl->setDefaultValue($this->editedCategory->getName());
		}
	}

	private function addParentCategoryControl()
	{
		$parentCategoryItems = [
			self::ROOT_CATEGORY_KEY => '',
		];
		foreach ($this->categoryService->getAll() as $category) {
			if ($this->editedCategory !== null) {
				if ($category->isSelfOrSubcategoryOf($this->editedCategory)) {
					continue;
				}
			}
			$parentCategoryItems[$category->getId()] = '';
		}
		$parentCategoryControl = $this->addRadioList('parentCategory', 'Parent category', $parentCategoryItems);
		$parentCategoryControl->setRequired();
		$parentCategoryControl->setDefaultValue(
			$this->editedCategory !== null && $this->editedCategory->hasParent()
				? $this->editedCategory->getParent()->getId()
				: self::ROOT_CATEGORY_KEY
		);
	}

}
