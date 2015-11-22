<?php

namespace ShoPHP;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class EntityService extends \Nette\Object
{

	/** @var ObjectRepository */
	protected $repository;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(ObjectRepository $repository, EntityManagerInterface $entityManager)
	{
		$this->repository = $repository;
		$this->entityManager = $entityManager;
	}

	protected function createEntity($entity)
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}

	protected function updateEntity($entity)
	{
		$this->entityManager->flush();
	}

}
