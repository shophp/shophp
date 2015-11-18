<?php

namespace ShoPHP\Repository;

use ShoPHP\Category;

class CategoryRepository extends \Doctrine\ORM\EntityRepository
{

	/**
	 * @return Category[]
	 */
	public function getRoot()
	{
		return $this->findBy([
			'parent' => null,
		]);
	}

	/**
	 * @param string $path
	 * @return Category
	 */
	public function findByPath($path)
	{
		return $this->findOneBy([
			'path' => $path,
		]);
	}

}
