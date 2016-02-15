<?php

namespace ShoPHP\Admin\Category;

use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\ArrayHash;
use ShoPHP\Product\CategoryService;

class ListPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var ManageCategoriesFormFactory */
	private $manageCategoriesFormFactory;

	public function __construct(CategoryService $categoryService, ManageCategoriesFormFactory $manageCategoriesFormFactory)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->manageCategoriesFormFactory = $manageCategoriesFormFactory;
	}

	public function actionDefault()
	{
	}

	public function renderDefault()
	{
		$this->template->categories = $this->categoryService->getRoot();
	}

	protected function createComponentManageCategoriesForm()
	{
		$form = $this->manageCategoriesFormFactory->create();
		$form->onSuccess[] = function(ManageCategoriesForm $form, ArrayHash $values) {
			$this->manageCategories($values, $form);
		};

		return $form;
	}

	private function manageCategories(ArrayHash $values, ManageCategoriesForm $form)
	{
		/** @var Container $deleteContainer */
		$deleteContainer = $form->getComponent('delete');

		foreach ($this->categoryService->getAll() as $category) {
			/** @var SubmitButton $deleteSubmit */
			$deleteSubmit = $deleteContainer->getComponent($category->getId(), false);
			if ($deleteSubmit !== null && $deleteSubmit->isSubmittedBy()) {
				$this->flashMessage(sprintf('Category %s has been deleted.', $category->getName()));
				$this->categoryService->remove($category);
				$this->redirect('this');
			}
		}
	}

}
