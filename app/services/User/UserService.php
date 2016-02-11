<?php

namespace ShoPHP\User;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\EntityDuplicateException;

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
	public function getById($id)
	{
		return $this->repository->find($id);
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

	public function create(User $user)
	{
		$this->checkDuplicity($user);
		$this->createEntity($user);
	}

	private function checkDuplicity(User $user)
	{
		$duplicate = $this->repository->findOneBy([
			'email' => $user->getEmail(),
		]);
		if ($duplicate !== null) {
			throw new EntityDuplicateException(sprintf('User with e-mail %s already exists.', $user->getEmail()));
		}
	}

}

