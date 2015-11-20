<?php

namespace ShoPHP\Admin\Category;

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

}
