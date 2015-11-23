<?php

namespace ShoPHP;

use Doctrine\ORM\EntityManagerInterface;

class CartService extends \ShoPHP\EntityService
{

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager->getRepository(Cart::class), $entityManager);
	}

	/**
	 * @param integer $id
	 * @return Category|null
	 */
	public function getById($id)
	{
		return $this->repository->find($id);
	}

}
