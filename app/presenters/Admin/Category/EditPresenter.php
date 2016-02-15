<?php

namespace ShoPHP\Admin\Category;

use Nette\Application\BadRequestException;
use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;
use ShoPHP\EntityDuplicateException;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var Category */
	private $category;

	/** @var CategoriesFormControlFactory */
	private $categoriesFormControlFactory;

	/** @var CategoryService */
	private $categoryService;

	public function __construct(CategoriesFormControlFactory $categoriesFormControlFactory, CategoryService $categoryService)
	{
		parent::__construct();
		$this->categoriesFormControlFactory = $categoriesFormControlFactory;
		$this->categoryService = $categoryService;
	}

	public function actionDefault($id)
	{
		if ($id !== null) {
			$this->category = $this->categoryService->getById($id);
		}
		if ($this->category === null) {
			throw new BadRequestException(sprintf('Category with ID %d not found.', $id));
		}
	}

	public function renderDefault()
	{
		$this->template->category = $this->category;
	}

	protected function createComponentCategoriesFormControl()
	{
		$control = $this->categoriesFormControlFactory->create($this->category);
		$form = $control->getForm();
		$form->onSuccess[] = function(CategoriesForm $form) {
			$this->updateCategory($form);
		};
		return $control;
	}

	private function updateCategory(CategoriesForm $form)
	{
		$values = $form->getValues();
		$this->category->setName($values->name);
		if ($values->parentCategory === CategoriesForm::ROOT_CATEGORY_KEY) {
			$parentCategory = null;
		} else {
			$parentCategory = $this->categoryService->getById($values->parentCategory);
		}
		$this->category->setParent($parentCategory);

		try {
			if (!$form->hasErrors()) {
				$this->categoryService->update($this->category);
				$this->flashMessage(sprintf('Category %s has been updated.', $this->category->getName()));
				$this->redirect(':Admin:Category:List:');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Category with name %s already exists.', $this->category->getName()));
		}
	}

}
