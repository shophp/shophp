<?php

namespace ShoPHP\User;

use Nette\Security\Passwords;
use Nette\Utils\Validators;
use ShoPHP\EntityInvalidArgumentException;

/**
 * @Entity
 * @Table(name="users")
 */
class User extends \Nette\Object implements \Nette\Security\IIdentity
{

	const PASSWORD_MIN_LENGTH = 3;

	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;

	/** @Column(type="string") */
	protected $email;

	/** @Column(type="string") */
	protected $password;

	public function __construct($email, $password)
	{
		$email = (string) $email;
		$password = (string) $password;
		if (!Validators::isEmail($email)) {
			throw new EntityInvalidArgumentException(sprintf('Invalid e-mail %s.', $email));
		}
		if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
			throw new EntityInvalidArgumentException(sprintf('Password must be at least %d characters long.', self::PASSWORD_MIN_LENGTH));
		}

		$this->email = $email;
		$this->password = Passwords::hash($password);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function equalsPassword($password)
	{
		return Passwords::verify($password, $this->password);
	}

	public function getRoles()
	{
		return [];
	}

}

