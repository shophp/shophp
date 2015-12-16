<?php

namespace ShoPHP;

use Nette\Security\AuthenticationException;
use ShoPHP\User\UserService;

class Authenticator extends \Nette\Object implements \Nette\Security\IAuthenticator
{

	/** @var UserService */
	private $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		$user = $this->userService->getByEmail($email);

		if ($user === null) {
			throw new AuthenticationException(sprintf('User with e-mail %s not found.', $email), self::IDENTITY_NOT_FOUND);
		}
		if (!$user->equalsPassword($password)) {
			throw new AuthenticationException('Invalid password.', self::INVALID_CREDENTIAL);
		}

		return $user;
	}

}
