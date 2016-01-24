<?php

namespace ShoPHP\Admin\Product;

use Nette\Http\FileUpload;
use ShoPHP\Product\CategoryService;
use ShoPHP\EntityDuplicateException;
use ShoPHP\Product\Product;
use ShoPHP\Product\ProductImageService;
use ShoPHP\Product\ProductService;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductService */
	private $productService;

	/** @var ProductImageService */
	private $productImageService;

	/** @var CategoryService */
	private $categoryService;

	public function __construct(
		ProductFormControlFactory $productFormControlFactory,
		ProductService $productService,
		ProductImageService $productImageService,
		CategoryService $categoryService
	)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productService = $productService;
		$this->productImageService = $productImageService;
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
		/** @var FileUpload $fileUpload */
		foreach ($values->imagesUpload as $fileUpload) {
			$this->productImageService->create($product, $fileUpload);
		}

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
