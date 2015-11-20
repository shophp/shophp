<?php

namespace ShoPHP\Admin\Category;

use Nette\Localization\ITranslator;
use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

class CategoriesFormControl extends \ShoPHP\BaseControl
{

	/** @var string */
	private $submitLabel;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var CategoriesFormFactory */
	private $categoriesFormFactory;

	public function __construct($submitLabel, CategoryRepository $categoryRepository, CategoriesFormFactory $categoriesFormFactory, ITranslator $translator)
	{
		parent::__construct($translator);
		$this->submitLabel = $submitLabel;
		$this->categoryRepository = $categoryRepository;
		$this->categoriesFormFactory = $categoriesFormFactory;
	}

	public function setCurrentCategory(Category $category)
	{
		$this->template->currentCategory = $category;
	}

	/**
	 * @return CategoriesForm
	 */
	public function getForm()
	{
		return $this->getComponent('categoriesForm');
	}

	protected function createComponentCategoriesForm()
	{
		return $this->categoriesFormFactory->create($this->submitLabel);
	}

	public function render()
	{
		$this->template->categories = $this->categoryRepository->getRoot();
		$this->template->rootCategoryKey = CategoriesForm::ROOT_CATEGORY_KEY;
		$this->template->setFile(__DIR__ . '/CategoriesFormControl.latte');
		$this->template->render();
	}

}
