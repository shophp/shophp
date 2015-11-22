<?php

namespace ShoPHP\Admin\Category;

use Nette\Localization\ITranslator;
use ShoPHP\Category;
use ShoPHP\CategoryService;

class CategoriesFormControl extends \ShoPHP\BaseControl
{

	/** @var Category|null */
	private $editedCategory;

	/** @var CategoryService */
	private $categoryService;

	/** @var CategoriesFormFactory */
	private $categoriesFormFactory;

	public function __construct(
		CategoryService $categoryService,
		CategoriesFormFactory $categoriesFormFactory,
		ITranslator $translator,
		Category $editedCategory = null
	)
	{
		parent::__construct($translator);
		$this->categoryService = $categoryService;
		$this->categoriesFormFactory = $categoriesFormFactory;
		$this->editedCategory = $editedCategory;
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
		return $this->categoriesFormFactory->create($this->editedCategory);
	}

	public function render()
	{
		$this->template->categories = $this->categoryService->getRoot();
		$this->template->editedCategory = $this->editedCategory;
		$this->template->rootCategoryKey = CategoriesForm::ROOT_CATEGORY_KEY;
		$this->template->setFile(__DIR__ . '/CategoriesFormControl.latte');
		$this->template->render();
	}

}
