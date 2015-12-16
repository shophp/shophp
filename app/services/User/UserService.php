<?php

namespace ShoPHP\User;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(User::class);
	}

	/**
	 * @return User
	 */
	public function getByEmail($email)
	{
		return $this->repository->findOneBy([
			'email' => $email,
		]);
	}

}

