<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product;
use ShoPHP\Repository\ProductRepository;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductRepository */
	private $productRepository;

	public function __construct(ProductFormControlFactory $productFormControlFactory, ProductRepository $productRepository)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productRepository = $productRepository;
	}

	public function actionDefault()
	{
	}

	protected function createComponentProductFormControl()
	{
		$control = $this->productFormControlFactory->create('Create');
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
		$product->setDiscountPercent($values->discount);
		$product->setCategories($form->getCategories());

		if (!$form->hasErrors()) {
			$this->productRepository->create($product);
			$this->flashMessage(sprintf('Product %s has been created.', $product->getName()));
			$this->redirect('Edit:', ['id' => $product->getId()]);
		}
	}

}
