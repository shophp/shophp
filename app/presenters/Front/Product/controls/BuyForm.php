<?php

namespace ShoPHP\Front\Product;

class BuyForm extends \Nette\Application\UI\Form
{

	public function __construct()
	{
		parent::__construct();
		$this->createFields();
	}

	private function createFields()
	{
		$this->addAmountControl();
		$this->addSubmit('add', 'Add');
	}

	private function addAmountControl()
	{
		$errorMessage = 'Amount must be positive number.';
		$this->addText('amount', 'Amount')
			->setDefaultValue(1)
			->addRule(self::INTEGER, $errorMessage)
			->addRule(self::RANGE, $errorMessage, [1, null]);
	}

}
