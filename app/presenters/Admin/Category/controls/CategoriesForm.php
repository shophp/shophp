<?php

namespace ShoPHP\Admin\Category;

use Nette\Forms\Controls\RadioList;
use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

class CategoriesForm extends \Nette\Application\UI\Form
{

	const ROOT_CATEGORY_KEY = 'root';

	/** @var CategoryRepository */
	private $categoryRepository;

	/**
	 * @param string $submitLabel
	 */
	public function __construct($submitLabel, CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->categoryRepository = $categoryRepository;
		$this->createFields($submitLabel);
	}

	public function setDefaultsFromCategory(Category $category)
	{
		$defaults = [
			'name' => $category->getName(),
		];
		if ($category->hasParent()) {
			$defaults['parentCategory'] = $category->getParent()->getId();
		}

		$parentCategoryItems = $this->getParentCategoryControl()->getItems();
		unset($parentCategoryItems[$category->getId()]);
		$eraseSubcategories = function (Category $category) use (& $eraseSubcategories, & $parentCategoryItems) {
			if ($category->hasSubcategories()) {
				foreach ($category->getSubcategories() as $subcategory) {
					unset($parentCategoryItems[$subcategory->getId()]);
					$eraseSubcategories($subcategory);
				}
			}
		};
		$eraseSubcategories($category);
		$this->getParentCategoryControl()->setItems($parentCategoryItems);

		$this->getParent()->setCurrentCategory($category);

		$this->setDefaults($defaults);
	}

	/**
	 * @param string $submitLabel
	 */
	private function createFields($submitLabel)
	{
		$this->addText('name', 'Name')
			->setRequired();

		$parentCategoryItems = [
			self::ROOT_CATEGORY_KEY => '',
		];
		foreach ($this->categoryRepository->getAll() as $category) {
			$parentCategoryItems[$category->getId()] = '';
		}
		$this->addRadioList('parentCategory', 'Parent category', $parentCategoryItems)
			->setRequired()
			->setDefaultValue(self::ROOT_CATEGORY_KEY);

		$this->addSubmit('send', $submitLabel);
	}

	/**
	 * @return RadioList
	 */
	private function getParentCategoryControl()
	{
		return $this->getComponent('parentCategory');
	}

}
