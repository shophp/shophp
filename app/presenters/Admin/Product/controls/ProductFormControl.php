<?php

namespace ShoPHP\Admin\Product;

use Nette\Localization\ITranslator;
use ShoPHP\Product;
use ShoPHP\Repository\CategoryRepository;

class ProductFormControl extends \ShoPHP\BaseControl
{

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var ProductFormFactory */
	private $productFormFactory;

	/** @var Product|null */
	private $editedProduct;

	public function __construct(
		CategoryRepository $categoryRepository,
		ProductFormFactory $productFormFactory,
		ITranslator $translator,
		Product $editedProduct = null
	)
	{
		parent::__construct($translator);
		$this->categoryRepository = $categoryRepository;
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
		$this->template->categories = $this->categoryRepository->getRoot();
		$this->template->setFile(__DIR__ . '/ProductFormControl.latte');
		$this->template->render();
	}

}
