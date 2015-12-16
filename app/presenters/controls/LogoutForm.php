<?php

namespace ShoPHP;

class LogoutForm extends \Nette\Application\UI\Form
{

	public function __construct()
	{
		parent::__construct();
		$this->createFields();
	}

	private function createFields()
	{
		$this->addSubmit('logout', 'Logout');
	}

}
