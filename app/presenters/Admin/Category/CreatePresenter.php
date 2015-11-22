<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Category;
use ShoPHP\CategoryService;
use ShoPHP\EntityDuplicateException;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

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

	public function actionDefault()
	{
	}

	protected function createComponentCategoriesFormControl()
	{
		$control = $this->categoriesFormControlFactory->create();
		$form = $control->getForm();
		$form->onSuccess[] = function(CategoriesForm $form) {
			$this->createCategory($form);
		};
		return $control;
	}

	private function createCategory(CategoriesForm $form)
	{
		$values = $form->getValues();
		$category = new Category($values->name);
		if ($values->parentCategory !== CategoriesForm::ROOT_CATEGORY_KEY) {
			$parentCategory = $this->categoryService->getById($values->parentCategory);
			$category->setParent($parentCategory);
		}

		try {
			if (!$form->hasErrors()) {
				$this->categoryService->create($category);
				$this->flashMessage(sprintf('Category %s has been created.', $category->getName()));
				$this->redirect('this');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Category with name %s already exists.', $category->getName()));
		}
	}

}
