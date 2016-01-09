<?php

namespace ShoPHP\User;

use Doctrine\Common\Collections\ArrayCollection;
use Nette\Security\Passwords;
use Nette\Utils\Validators;
use ShoPHP\EntityInvalidArgumentException;
use ShoPHP\Order\Cart;

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

	/** @Column(type="string", nullable=true) */
	protected $name;

	/** @Column(type="string", nullable=true) */
	protected $street;

	/** @Column(type="string", nullable=true) */
	protected $city;

	/** @Column(type="string", nullable=true) */
	protected $zip;

	/**
	 * @OneToMany(targetEntity="\ShoPHP\Order\Cart", mappedBy="user")
	 * @var Cart[]
	 */
	protected $carts;

	public function __construct($email, $password)
	{
		$email = (string)$email;
		$password = (string)$password;
		if (!Validators::isEmail($email)) {
			throw new EntityInvalidArgumentException(sprintf('Invalid e-mail %s.', $email));
		}
		if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
			throw new EntityInvalidArgumentException(sprintf('Password must be at least %d characters long.', self::PASSWORD_MIN_LENGTH));
		}

		$this->email = $email;
		$this->password = Passwords::hash($password);

		$this->carts = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function hasAddress()
	{
		return $this->getName() !== null;
	}

	public function getName()
	{
		return $this->name;
	}

	public function hasName()
	{
		return $this->getName() !== null;
	}

	public function setName($name)
	{
		$name = (string) $name;
		if ($name === '') {
			$name = null;
		}
		$this->name = $name;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function hasStreet()
	{
		return $this->getStreet() !== null;
	}

	public function setStreet($street)
	{
		$street = (string) $street;
		if ($street === '') {
			$street = null;
		}
		$this->street = $street;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function hasCity()
	{
		return $this->getCity() !== null;
	}

	public function setCity($city)
	{
		$city = (string) $city;
		if ($city === '') {
			$city = null;
		}
		$this->city = $city;
	}

	public function getZip()
	{
		return $this->zip;
	}

	public function hasZip()
	{
		return $this->getZip() !== null;
	}

	public function setZip($zip)
	{
		$zip = (string) $zip;
		if ($zip === '') {
			$zip = null;
		}
		$this->zip = $zip;
	}

	public function equalsPassword($password)
	{
		return Passwords::verify($password, $this->password);
	}

	public function getRoles()
	{
		return [];
	}

	public function hasAnyCart()
	{
		return count($this->getCarts()) > 0;
	}

	public function getCarts()
	{
		return $this->carts;
	}

	public function getLastCart()
	{
		if (!$this->hasAnyCart()) {
			return null;
		}

		return $this->getCarts()->last();
	}

}

