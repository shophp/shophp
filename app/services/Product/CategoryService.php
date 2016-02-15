<?php

namespace ShoPHP\Product;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\EntityCannotBeDeletedException;
use ShoPHP\EntityDuplicateException;

class CategoryService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(Category::class);
	}

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

	public function remove(Category $category)
	{
		if ($category->hasProducts()) {
			throw new EntityCannotBeDeletedException('Category has products.');
		}
		foreach ($category->getSubcategories() as $subcategory) {
			$this->remove($subcategory);
		}
		$this->removeEntity($category);
	}

	/**
	 * @param integer $id
	 * @return Category|null
	 */
	public function getById($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @param int[] $ids
	 * @return Categories|Category[]
	 */
	public function getByIds(array $ids)
	{
		$categories = $this->repository->findBy([
			'id' => $ids,
		]);
		return new Categories($categories);
	}

	/**
	 * @return Categories|Category[]
	 */
	public function getAll()
	{
		return new Categories($this->repository->findAll());
	}

	/**
	 * @return Category[]
	 */
	public function getRoot()
	{
		return $this->repository->findBy([
			'parent' => null,
		]);
	}

	/**
	 * @param string $path
	 * @return Category
	 */
	public function getByPath($path)
	{
		return $this->repository->findOneBy([
			'path' => $path,
		]);
	}

	public function hasDuplicity(Category $category)
	{
		$duplicate = $this->repository->findOneBy([
			'path' => $category->getPath(),
		]);
		return $duplicate !== null;
	}

	private function checkDuplicity(Category $category)
	{
		$duplicate = $this->repository->findOneBy([
			'path' => $category->getPath(),
		]);
		if ($duplicate !== null) {
			throw new EntityDuplicateException(sprintf('Category with path %s already exists.', $category->getPath()));
		}
	}

}
