<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\CategoryService;
use ShoPHP\EntityDuplicateException;
use ShoPHP\Product;
use ShoPHP\ProductService;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductService */
	private $productService;

	/** @var CategoryService */
	private $categoryService;

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

	public function actionDefault()
	{
	}

	protected function createComponentProductFormControl()
	{
		$control = $this->productFormControlFactory->create();
		$form = $control->getForm();
		$form->onSuccess[] = function(ProductForm $form) {
			$this->createProduct($form);
		};
		return $control;
	}

	private function createProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$product = new Product($values->name, $values->price);
		$product->setDescription($values->description);
		if ($values->discountType === ProductForm::DISCOUNT_PERCENT) {
			$product->setDiscountPercent($values->discountPercent);
		} else {
			$product->setNominalDiscount($values->nominalDiscount);
		}
		$product->setCategories($this->categoryService->getByIds($values->categories));

		try {
			if (!$form->hasErrors()) {
				$this->productService->create($product);
				$this->flashMessage(sprintf('Product %s has been created.', $product->getName()));
				$this->redirect('Edit:', ['id' => $product->getId()]);
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Product with name %s already exists.', $product->getName()));
		}
	}

}
