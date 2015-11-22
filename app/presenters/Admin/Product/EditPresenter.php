<?php

namespace ShoPHP\Admin\Product;

use Nette\Application\BadRequestException;
use ShoPHP\CategoryService;
use ShoPHP\EntityDuplicateException;
use ShoPHP\Product;
use ShoPHP\ProductService;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductService */
	private $productService;

	/** @var CategoryService */
	private $categoryService;

	/** @var Product */
	private $product;

	public function __construct(
		ProductFormControlFactory $productFormControlFactory,
		ProductService $productService,
		CategoryService $categoryService
	)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productService = $productService;
		$this->categoryService = $categoryService;
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id)
	{
		if ($id !== null) {
			$this->product = $this->productService->getById($id);
		}
		if ($this->product === null) {
			throw new BadRequestException(sprintf('Product with ID %d not found.', $id));
		}
	}

	public function renderDefault()
	{
		$this->template->product = $this->product;
	}

	protected function createComponentProductFormControl()
	{
		$control = $this->productFormControlFactory->create($this->product);
		$form = $control->getForm();
		$form->onSuccess[] = function(ProductForm $form) {
			$this->updateProduct($form);
		};
		return $control;
	}

	private function updateProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$this->product->setName($values->name);
		$this->product->setPrice($values->price);
		$this->product->setDescription($values->description);
		$this->product->setDiscountPercent($values->discount);
		$this->product->setCategories($this->categoryService->getByIds($values->categories));

		try {
			if (!$form->hasErrors()) {
				$this->productService->update($this->product);

				$this->flashMessage(sprintf('Product %s has been updated.', $this->product->getName()));
				$this->redirect('this');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Product with name %s already exists.', $this->product->getName()));
		}
	}

}
