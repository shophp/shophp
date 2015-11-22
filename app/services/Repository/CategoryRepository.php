<?php

namespace ShoPHP\Repository;

use ShoPHP\Categories;
use ShoPHP\Category;
use ShoPHP\EntityDuplicateException;

class CategoryRepository extends \ShoPHP\Repository
{

	public function create(Category $category)
	{
		$this->checkDuplicity($category);
		$this->createEntity($category);
	}

	public function update(Category $category)
	{
		$this->checkDuplicity($category);
		$this->updateEntity($category);
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
	 * @param int[] $ids
	 * @return Categories|Category[]
	 */
	public function getByIds(array $ids)
	{
		$categories = $this->findBy([
			'id' => $ids,
		]);
		return new Categories($categories);
	}

	/**
	 * @return Categories|Category[]
	 */
	public function getAll()
	{
		return new Categories($this->findAll());
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

	private function checkDuplicity(Category $category)
	{
		$duplicate = $this->findOneBy([
			'path' => $category->getPath(),
		]);
		if ($duplicate !== null) {
			throw new EntityDuplicateException(sprintf('Category with name %s already exists.', $category->getName()));
		}
	}

}
