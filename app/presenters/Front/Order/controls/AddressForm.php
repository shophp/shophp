<?php

namespace ShoPHP\Front\Order;

use ShoPHP\AddressFormContainer;
use ShoPHP\Order\Cart;

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
		$addressContainer = new AddressFormContainer();
		$this->addComponent($addressContainer, 'address');
		$addressContainer->addNameControl('name', $this->cart->getName(), true);
		$addressContainer->addStreetControl('street', $this->cart->getStreet(), true);
		$addressContainer->addCityControl('city', $this->cart->getCity(), true);
		$addressContainer->addZipControl('zip', $this->cart->getZip(), true);
		$this->addSubmit('next', 'Next');
	}

}
