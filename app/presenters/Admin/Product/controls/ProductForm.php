<?php

namespace ShoPHP\Admin\Product;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\TextInput;
use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;
use ShoPHP\Product\Product;

class ProductForm extends \Nette\Application\UI\Form
{

	const DISCOUNT_PERCENT = 0;
	const DISCOUNT_NOMINAL = 1;

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
		$priceControl = $this->addOriginalPriceControl();
		$this->addDiscountControls($priceControl);
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
		$control = $this->addText('price', 'Original price');
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
		return $control;
	}

	private function addDiscountControls(BaseControl $priceControl)
	{
		$discountTypeControl = $this->addRadioList('discountType', null, [
			self::DISCOUNT_PERCENT => null,
			self::DISCOUNT_NOMINAL => null,
		]);
		$discountTypeControl->setDefaultValue(self::DISCOUNT_PERCENT);

		$this->addDiscountPercentControl();
		$this->addNominalDiscountControl($priceControl);
	}

	private function addDiscountPercentControl()
	{
		$errorMessage = 'Discount percent must be number between 0 and 100.';
		$control = $this->addText('discountPercent', 'Discount percent');
		$control->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue(0)
			->addRule(self::FLOAT, $errorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() >= 0 && $input->getValue() < 100;
			}, $errorMessage);
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getDiscountPercent());
		}
	}

	private function addNominalDiscountControl(BaseControl $priceControl)
	{
		$errorMessage = 'Nominal discount must be between 0 and original price.';
		$control = $this->addText('nominalDiscount', 'Nominal discount');
		$control->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue(0)
			->addRule(self::FLOAT, $errorMessage)
			->addRule(function (TextInput $input) use ($priceControl) {
				return $input->getValue() >= 0 && $input->getValue() < $priceControl->getValue();
			}, $errorMessage);
		if ($this->editedProduct !== null) {
			$control->setDefaultValue($this->editedProduct->getNominalDiscount());
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
