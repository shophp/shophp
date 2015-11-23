<?php

namespace ShoPHP\Admin\Product;

use Nette\Forms\Controls\TextInput;
use ShoPHP\Category;
use ShoPHP\CategoryService;
use ShoPHP\Product;

class ProductForm extends \Nette\Application\UI\Form
{

	/** @var CategoryService */
	private $categoryService;

	/** @var Product|null */
	private $editedProduct;

	public function __construct(CategoryService $categoryService, Product $editedProduct = null)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->editedProduct = $editedProduct;
		$this->createFields();
	}

	private function createFields()
	{
		$this->addNameControl();
		$this->addDescriptionControl();
		$this->addOriginalPriceControl();
		$this->addDiscountPercentControl();
		$this->addCategoriesControl();
		$this->addSubmit('send', $this->editedProduct !== null ? 'Update' : 'Create');
	}

	private function addNameControl()
	{
		$control = $this->addText('name', 'Name');
		$control->setRequired();
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getName());
		}
	}

	private function addDescriptionControl()
	{
		$control = $this->addTextArea('description', 'Description');
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getDescription());
		}
	}

	private function addOriginalPriceControl()
	{
		$errorMessage = 'Price must be positive number.';
		$control = $this->addText('price', 'Price');
		$control->setType('number')
			->setAttribute('step', 'any')
			->setRequired()
			->addRule(self::FLOAT, $errorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() > 0;
			}, $errorMessage);
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getOriginalPrice());
		}
	}

	private function addDiscountPercentControl()
	{
		$errorMessage = 'Discount must be number between 0 and 100.';
		$control = $this->addText('discount', 'Discount');
		$control->setType('number')
			->setDefaultValue(0)
			->addRule(self::INTEGER, $errorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() >= 0 && $input->getValue() < 100;
			}, $errorMessage);
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getDiscountPercent());
		}
	}

	private function addCategoriesControl()
	{
		$categoryIds = iterator_to_array($this->categoryService->getAll());
		$categoryIds = array_map(function (Category $category) {
			return $category->getId();
		}, $categoryIds);
		$control = $this->addCheckboxList('categories', 'Categories', array_flip($categoryIds));
		if ($this->editedProduct !== null) {
			$checkedIds = iterator_to_array($this->editedProduct->getCategories());
			$checkedIds = array_map(function (Category $category) {
				return $category->getId();
			}, $checkedIds);
			$control->setDefaultValue($checkedIds);
		}
	}

}
