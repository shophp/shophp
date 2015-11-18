<?php

namespace ShoPHP\Admin\Product;

use Nette\Forms\Controls\TextInput;

class ProductForm extends \Nette\Application\UI\Form
{

	/**
	 * @param string $submitLabel
	 */
	public function __construct($submitLabel)
	{
		parent::__construct();

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
