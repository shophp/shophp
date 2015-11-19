<?php

namespace ShoPHP\Admin\Product;

use Nette\Localization\ITranslator;
use ShoPHP\Repository\CategoryRepository;

class ProductFormControl extends \ShoPHP\BaseControl
{

	/** @var string */
	private $submitLabel;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var ProductFormFactory */
	private $productFormFactory;

	public function __construct($submitLabel, CategoryRepository $categoryRepository, ProductFormFactory $productFormFactory, ITranslator $translator)
	{
		parent::__construct($translator);
		$this->submitLabel = $submitLabel;
		$this->categoryRepository = $categoryRepository;
		$this->productFormFactory = $productFormFactory;
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
		return $this->productFormFactory->create($this->submitLabel);
	}

	public function render()
	{
		$this->template->categories = $this->categoryRepository->getRoot();
		$this->template->setFile(__DIR__ . '/ProductFormControl.latte');
		$this->template->render();
	}

}