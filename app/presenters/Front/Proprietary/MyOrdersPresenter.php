<?php

namespace ShoPHP\Front\Proprietary;

use Nette\Security\User;

class MyOrdersPresenter extends \ShoPHP\Front\Proprietary\BasePresenter
{

	/** @var User */
	private $user;

	public function __construct(
		User $user
	)
	{
		parent::__construct();
		$this->user = $user;
	}

	public function renderDefault()
	{
		$this->template->customer = $this->user->getIdentity();
	}

}
