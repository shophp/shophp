<?php

namespace ShoPHP\Front\Order;

use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use ShoPHP\Order\Cart;

class CartForm extends \Nette\Application\UI\Form
{

	/** @var Cart */
	private $cart;

	public function __construct(Cart $cart)
	{
		parent::__construct();
		$this->cart = $cart;
		$this->createFields();
	}

	public function getRemoveItemId()
	{
		/** @var Container $removeContainer */
		$removeContainer = $this->getComponent('remove');
		/** @var SubmitButton $control */
		foreach ($removeContainer->getControls() as $itemId => $control) {
			if ($control->isSubmittedBy()) {
				return (int) $itemId;
			}
		}
		return null;
	}

	private function createFields()
	{
		$this->addCartEraseControls();
		$this->addCartAmountControls();
		$this->addSubmit('recalculate', 'Recalculate');
	}

	private function addCartEraseControls()
	{
		$container = $this->addContainer('remove');
		foreach ($this->cart->getItems() as $item) {
			$container->addSubmit($item->getId(), 'x');
		}
		return $container;
	}

	private function addCartAmountControls()
	{
		$container = $this->addContainer('amount');
		foreach ($this->cart->getItems() as $item) {
			$errorMessage = 'Amount must be positive number.';
			$container->addText($item->getId(), 'Amount')
				->setType('number')
				->setDefaultValue($item->getAmount())
				->addRule(self::INTEGER, $errorMessage)
				->addRule(self::RANGE, $errorMessage, [1, null]);
		}
		return $container;
	}

}
