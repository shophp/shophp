<?php

namespace ShoPHP\Admin\Product;

use Nette\Forms\Controls\TextInput;
use ShoPHP\Categories;
use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

class ProductForm extends \Nette\Application\UI\Form
{

	/** @var CategoryRepository */
	private $categoryRepository;

	/**
	 * @param string $submitLabel
	 */
	public function __construct($submitLabel, CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->categoryRepository = $categoryRepository;
		$this->createFields($submitLabel);
	}

	/**
	 * @return Categories|Category[]
	 */
	public function getCategories()
	{
		$categoryIds = $this->getHttpData(self::DATA_TEXT, 'category[]');
		$categories = new Categories();
		foreach ($categoryIds as $categoryId) {
			$category = $this->categoryRepository->getById($categoryId);
			if ($category === null) {
				$this->addError(sprintf('Category %d does not exist.', $categoryId));
			} else {
				$categories[] = $category;
			}
		}
		return $categories;
	}

	/**
	 * @param string $submitLabel
	 */
	private function createFields($submitLabel)
	{
		$this->addText('name', 'Name')
			->setRequired();

		$this->addTextArea('description', 'Description');

		$priceErrorMessage = 'Price must be positive number.';
		$this->addText('price', 'Price')
			->setType('number')
			->setAttribute('step', 'any')
			->setRequired()
			->addRule(self::FLOAT, $priceErrorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() > 0;
			}, $priceErrorMessage);

		$discountErrorMessage = 'Discount must be number between 0 and 100.';
		$this->addText('discount', 'Discount')
			->setType('number')
			->setDefaultValue(0)
			->addRule(self::INTEGER, $discountErrorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() >= 0 && $input->getValue() < 100;
			}, $discountErrorMessage);

		$this->addSubmit('send', $submitLabel);
	}

}
