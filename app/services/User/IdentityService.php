<?php

namespace ShoPHP\User;

use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\IUserStorage;

class IdentityService extends \Nette\Security\User
{

	/** @var UserService */
	private $userService;

	public function __construct(
		IUserStorage $storage,
		IAuthenticator $authenticator = null,
		IAuthorizator $authorizator = null,
		UserService $userService
	)
	{
		parent::__construct($storage, $authenticator, $authorizator);
		$this->userService = $userService;
	}

	public function getIdentity()
	{
		$identity = parent::getIdentity();
		if ($identity !== null) {
			$identity = $this->userService->getById((int) $identity->getId());
		}
		return $identity;
	}

}
