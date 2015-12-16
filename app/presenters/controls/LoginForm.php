<?php

namespace ShoPHP;

class LoginForm extends \Nette\Application\UI\Form
{

	public function __construct()
	{
		parent::__construct();
		$this->createFields();
	}

	private function createFields()
	{
		$this->addEmailControl();
		$this->addPasswordControl();
		$this->addPermanentControl();
		$this->addSubmit('login', 'Login');
	}

	private function addEmailControl()
	{
		$control = $this->addText('email', 'E-mail');
		$control->setRequired();
		return $control;
	}

	private function addPasswordControl()
	{
		$control = $this->addPassword('password', 'Password');
		$control->setRequired();
		return $control;
	}

	private function addPermanentControl()
	{
		$this->addCheckbox('permanent', 'Do not logout');
	}

}
