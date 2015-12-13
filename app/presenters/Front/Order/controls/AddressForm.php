<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Cart;

class AddressForm extends \Nette\Application\UI\Form
{

	use \ShoPHP\AddressForm;

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
		$this->addNameControl('name', $this->cart->getName(), true);
		$this->addStreetControl('street', $this->cart->getStreet(), true);
		$this->addCityControl('city', $this->cart->getCity(), true);
		$this->addZipControl('zip', $this->cart->getZip(), true);
		$this->addSubmit('next', 'Next');
	}

}
