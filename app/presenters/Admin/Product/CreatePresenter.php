<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product;
use ShoPHP\Repository\ProductRepository;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormFactory */
	private $productFormFactory;

	/** @var ProductRepository */
	private $productRepository;

	public function __construct(ProductFormFactory $productFormFactory, ProductRepository $productRepository)
	{
		parent::__construct();
		$this->productFormFactory = $productFormFactory;
		$this->productRepository = $productRepository;
	}

	public function actionDefault()
	{
	}

	protected function createComponentProductForm()
	{
		$form = $this->productFormFactory->create('Create');
		$form->onSuccess[] = function(ProductForm $form) {
			$this->createProduct($form);
		};
		return $form;
	}

	private function createProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$product = new Product($values->name, $values->price);
		$product->setDescription($values->description);
		$product->setDiscountPercent($values->discount);
		$this->productRepository->create($product);

		$this->flashMessage(sprintf('Product %s has been created.', $product->getName()));
		$this->redirect('Edit:', ['id' => $product->getId()]);
	}

}
