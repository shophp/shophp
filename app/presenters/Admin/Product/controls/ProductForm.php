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

	public function __construct(CategoryService $categoryService, Product $editedProduct = null)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->createFields($editedProduct);
	}

	private function createFields(Product $editedProduct = null)
	{
		$nameControl = $this->addText('name', 'Name');
		$nameControl->setRequired();
		if ($editedProduct !== null) {
			$nameControl->setDefaultValue($editedProduct->getName());
		}

		$descriptionControl = $this->addTextArea('description', 'Description');
		if ($editedProduct !== null) {
			$descriptionControl->setDefaultValue($editedProduct->getDescription());
		}

		$priceErrorMessage = 'Price must be positive number.';
		$priceControl = $this->addText('price', 'Price');
		$priceControl->setType('number')
			->setAttribute('step', 'any')
			->setRequired()
			->addRule(self::FLOAT, $priceErrorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() > 0;
			}, $priceErrorMessage);
		if ($editedProduct !== null) {
			$priceControl->setDefaultValue($editedProduct->getOriginalPrice());
		}

		$discountErrorMessage = 'Discount must be number between 0 and 100.';
		$discountControl = $this->addText('discount', 'Discount');
		$discountControl->setType('number')
			->setDefaultValue(0)
			->addRule(self::INTEGER, $discountErrorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() >= 0 && $input->getValue() < 100;
			}, $discountErrorMessage);
		if ($editedProduct !== null) {
			$discountControl->setDefaultValue($editedProduct->getDiscountPercent());
		}

		$categoryIds = iterator_to_array($this->categoryService->getAll());
		$categoryIds = array_map(function (Category $category) {
			return $category->getId();
		}, $categoryIds);
		$categoriesControl = $this->addCheckboxList('categories', 'Categories', array_flip($categoryIds));
		if ($editedProduct !== null) {
			$checkedIds = iterator_to_array($editedProduct->getCategories());
			$checkedIds = array_map(function (Category $category) {
				return $category->getId();
			}, $checkedIds);
			$categoriesControl->setDefaultValue($checkedIds);
		}

		$this->addSubmit('send', $editedProduct !== null ? 'Update' : 'Create');
	}

}
