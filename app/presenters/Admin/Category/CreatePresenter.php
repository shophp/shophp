<?php

namespace ShoPHP\Admin\Category;

use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var CategoriesFormControlFactory */
	private $categoriesFormControlFactory;

	/** @var CategoryRepository */
	private $categoryRepository;

	public function __construct(CategoriesFormControlFactory $categoriesFormControlFactory, CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->categoriesFormControlFactory = $categoriesFormControlFactory;
		$this->categoryRepository = $categoryRepository;
	}

	public function actionDefault()
	{
	}

	protected function createComponentCategoriesFormControl()
	{
		$control = $this->categoriesFormControlFactory->create('Create');
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
			$parentCategory = $this->categoryRepository->getById($values->parentCategory);
			if ($parentCategory === null) {
				$form->addError(sprintf('Parent category %d does not exist.', $parentCategory));
			} else {
				$category->setParent($parentCategory);
			}
		}
		if ($this->categoryRepository->hasDuplicity($category)) {
			$form->addError(sprintf('Category with name %s already exists.', $category->getName()));
		}

		if (!$form->hasErrors()) {
			$this->categoryRepository->create($category);
			$this->flashMessage(sprintf('Category %s has been created.', $category->getName()));
			$this->redirect('this');
		}
	}

}
