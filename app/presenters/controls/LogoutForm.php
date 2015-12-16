<?php

namespace ShoPHP;

class LogoutForm extends \Nette\Application\UI\Form
{

	/** @var \Nette\Security\User*/
	private $user;

	public function __construct(\Nette\Security\User $user)
	{
		parent::__construct();
		$this->user = $user;

		$this->createFields();
		$this->addEventListeners();
	}

	private function createFields()
	{
		$this->addSubmit('logout', 'Logout');
	}

	private function addEventListeners()
	{
		$this->onSuccess[] = function () {
			$this->user->logout();
		};
	}

}
