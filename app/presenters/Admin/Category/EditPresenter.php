<?php

namespace ShoPHP\Admin\Category;

use Nette\Application\BadRequestException;
use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var Category */
	private $category;

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

	public function actionDefault($id)
	{
		$this->category = $this->categoryRepository->getById($id);
		if ($this->category === null) {
			throw new BadRequestException(sprintf('Category %d not found.', $id));
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
			$parentCategory = $this->categoryRepository->getById($values->parentCategory);
			if ($parentCategory === null) {
				$form->addError(sprintf('Parent category %d does not exist.', $parentCategory));
			}
		}
		$this->category->setParent($parentCategory);

		if ($this->categoryRepository->hasDuplicity($this->category)) {
			$form->addError(sprintf('Category with name %s already exists.', $this->category->getName()));
		}
		if (!$form->hasErrors()) {
			$this->categoryRepository->flush();
			$this->flashMessage(sprintf('Category %s has been updated.', $this->category->getName()));
			$this->redirect('this');
		}
	}

}
