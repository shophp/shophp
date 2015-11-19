<?php

namespace ShoPHP\Repository;

use ShoPHP\Category;

class CategoryRepository extends \ShoPHP\Repository
{

	/**
	 * @param integer $id
	 * @return Category|null
	 */
	public function getById($id)
	{
		return $this->find($id);
	}

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
