<?php

namespace ShoPHP\Front\Proprietary;

use Nette\Security\User;

class BasePresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var User */
	private $user;

	public function injectProprietaryBase(User $user)
	{
		$this->user = $user;
	}

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->flashMessage('To enter the section please log in.');
			$this->redirect(':Front:Home:Homepage:');
		}
	}

}
