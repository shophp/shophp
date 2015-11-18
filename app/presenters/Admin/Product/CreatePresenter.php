<?php

namespace ShoPHP\Admin\Product;

use ShoPHP\Product;
use ShoPHP\Repository\ProductRepository;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var CreateFormFactory */
	private $createFormFactory;

	/** @var ProductRepository */
	private $productRepository;

	public function __construct(CreateFormFactory $createFormFactory, ProductRepository $productRepository)
	{
		parent::__construct();
		$this->createFormFactory = $createFormFactory;
		$this->productRepository = $productRepository;
	}

	public function actionDefault()
	{
	}

	protected function createComponentCreateForm()
	{
		$form = $this->createFormFactory->create();
		$form->onSuccess[] = function(CreateForm $form) {
			$this->createProduct($form);
		};
		return $form;
	}

	private function createProduct(CreateForm $form)
	{
		$values = $form->getValues();
		$product = new Product($values->name, $values->price);
		$product->setDescription($values->description);
		$product->setDiscountPercent($values->discount);
		$this->productRepository->create($product);

		$this->flashMessage(sprintf('Product %s has been created.', $product->getName()));
		$this->redirect('this');
	}

}
