<?php

namespace ShoPHP\Admin\Category;

use Nette\Forms\Controls\RadioList;
use ShoPHP\Category;
use ShoPHP\CategoryService;

class CategoriesForm extends \Nette\Application\UI\Form
{

	const ROOT_CATEGORY_KEY = 'root';

	/** @var CategoryService */
	private $categoryService;

	public function __construct(CategoryService $categoryService, Category $editedCategory = null)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->createFields($editedCategory);
	}

	private function createFields(Category $editedCategory = null)
	{
		$nameControl = $this->addText('name', 'Name');
		$nameControl->setRequired();
		if ($editedCategory !== null) {
			$nameControl->setDefaultValue($editedCategory->getName());
		}

		$parentCategoryItems = [
			self::ROOT_CATEGORY_KEY => '',
		];
		foreach ($this->categoryService->getAll() as $category) {
			if ($editedCategory !== null) {
				if ($category->isSelfOrSubcategoryOf($editedCategory)) {
					continue;
				}
			}
			$parentCategoryItems[$category->getId()] = '';
		}
		$parentCategoryControl = $this->addRadioList('parentCategory', 'Parent category', $parentCategoryItems);
		$parentCategoryControl->setRequired();
		$parentCategoryControl->setDefaultValue(
			$editedCategory !== null && $editedCategory->hasParent()
				? $editedCategory->getParent()->getId()
				: self::ROOT_CATEGORY_KEY
		);

		$this->addSubmit('send', $editedCategory === null ? 'Create' : 'Update');
	}

	/**
	 * @return RadioList
	 */
	private function getParentCategoryControl()
	{
		return $this->getComponent('parentCategory');
	}

}
