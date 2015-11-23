<?php

namespace ShoPHP;

use Doctrine\ORM\EntityManagerInterface;

abstract class EntityService extends \Nette\Object
{

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
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

	protected function removeEntity($entity)
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
	}

}
