<?php

namespace ShoPHP\Front\Order;

use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use ShoPHP\Cart;

class AddressForm extends \Nette\Application\UI\Form
{

	/** @var Cart */
	private $cart;

	public function __construct(Cart $cart)
	{
		parent::__construct();
		$this->cart = $cart;
		$this->createFields();
	}

	private function createFields()
	{
		$this->addNameControl();
		$this->addStreetControl();
		$this->addCityControl();
		$this->addZipControl();
		$this->addSubmit('next', 'Next');
	}

	private function addNameControl()
	{
		$control = $this->addText('name', 'Your name');
		$control->setRequired();
		if ($this->cart->getName()) {
			$control->setDefaultValue($this->cart->getName());
		}
	}

	private function addStreetControl()
	{
		$control = $this->addText('street', 'Street and house number');
		$control->setRequired();
		if ($this->cart->getStreet()) {
			$control->setDefaultValue($this->cart->getStreet());
		}
	}

	private function addCityControl()
	{
		$control = $this->addText('city', 'City');
		$control->setRequired();
		if ($this->cart->getCity()) {
			$control->setDefaultValue($this->cart->getCity());
		}
	}

	private function addZipControl()
	{
		$control = $this->addText('zip', 'Zip');
		$control->setRequired();
		if ($this->cart->getZip()) {
			$control->setDefaultValue($this->cart->getZip());
		}
	}

}
