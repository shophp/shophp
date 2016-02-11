<?php

namespace ShoPHP\Front\User;

use ShoPHP\AddressFormContainer;
use ShoPHP\User\User;

class RegistrationForm extends \Nette\Application\UI\Form
{

	public function __construct()
	{
		parent::__construct();
		$this->createFields();
	}

	private function createFields()
	{
		$this->addEmailControl();
		$this->addPasswordControls();
		$this->addAddressControls();
		$this->addSubmit('register', 'Register');
	}

	private function addEmailControl()
	{
		$this->addText('email', 'E-mail')
			->setRequired()
			->addRule(self::EMAIL);
	}

	private function addPasswordControls()
	{
		$passwordControl = $this->addPassword('password', 'Password')
			->setRequired()
			->addRule(self::MIN_LENGTH, 'Minimal length of password is %d characters.', User::PASSWORD_MIN_LENGTH);
		$this->addPassword('passwordVerify', 'Password again')
			->addRule(self::EQUAL, 'Passwords does not equal.', $passwordControl);
	}

	private function addAddressControls()
	{
		$addressContainer = new AddressFormContainer();
		$this->addComponent($addressContainer, 'address');
		$addressContainer->addNameControl('name', null, false);
		$addressContainer->addStreetControl('street', null, false);
		$addressContainer->addCityControl('city', null, false);
		$addressContainer->addZipControl('zip', null, false);
	}

}
