<?php

namespace ShoPHP;

abstract class Repository extends \Doctrine\ORM\EntityRepository
{

	protected function createEntity($entity)
	{
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
	}

	public function flush()
	{
		$this->getEntityManager()->flush();
	}

	protected function updateEntity($entity)
	{
		$this->getEntityManager()->flush();
	}

}
