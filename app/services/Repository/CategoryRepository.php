<?php

namespace ShoPHP\Repository;

use ShoPHP\Category;

class CategoryRepository extends \ShoPHP\Repository
{

	public function create(Category $category)
	{
		$this->createEntity($category);
	}

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
	public function getAll()
	{
		return $this->findAll();
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

	public function hasDuplicity(Category $category)
	{
		$duplicate = $this->findOneBy([
			'path' => $category->getPath(),
		]);
		return $duplicate !== null;
	}

}
