<?php

namespace ShoPHP\Admin\Product;

use Nette\Localization\ITranslator;
use ShoPHP\Product\CategoryService;
use ShoPHP\Product\Product;

class ProductFormControl extends \ShoPHP\BaseControl
{

	/** @var CategoryService */
	private $categoryService;

	/** @var ProductFormFactory */
	private $productFormFactory;

	/** @var Product|null */
	private $editedProduct;

	public function __construct(
		CategoryService $categoryService,
		ProductFormFactory $productFormFactory,
		ITranslator $translator,
		Product $editedProduct = null
	)
	{
		parent::__construct($translator);
		$this->categoryService = $categoryService;
		$this->productFormFactory = $productFormFactory;
		$this->editedProduct = $editedProduct;
	}

	/**
	 * @return ProductForm
	 */
	public function getForm()
	{
		return $this->getComponent('productForm');
	}

	protected function createComponentProductForm()
	{
		return $this->productFormFactory->create($this->editedProduct);
	}

	public function render()
	{
		$this->template->categories = $this->categoryService->getRoot();
		$this->template->formDiscountPercentKey = ProductForm::DISCOUNT_PERCENT;
		$this->template->formDiscountNominalKey = ProductForm::DISCOUNT_NOMINAL;
		$this->template->setFile(__DIR__ . '/ProductFormControl.latte');
		$this->template->render();
	}

}
