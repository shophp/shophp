<?php

namespace ShoPHP;

abstract class Repository extends \Doctrine\ORM\EntityRepository
{

	protected function createEntity($entity)
	{
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
	}

}
