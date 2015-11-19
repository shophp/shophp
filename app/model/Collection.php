<?php

namespace ShoPHP;

abstract class Collection extends \Doctrine\Common\Collections\ArrayCollection
{

	/** @var string */
	private $entityClass;

	public function __construct(array $entities = [])
	{
		$this->entityClass = (string) $this->getEntityClass();
		if (!class_exists($this->entityClass)) {
			throw new CollectionException(sprintf('Class %s does not exist.', $this->entityClass));
		}
		foreach ($entities as $entity) {
			$this->checkInstanceOf($entity);
		}

		parent::__construct($entities);
	}

	/**
	 * @return string
	 */
	abstract protected function getEntityClass();

	public function add($entity)
	{
		$this->checkInstanceOf($entity);
		return parent::add($entity);
	}

	public function set($key, $entity)
	{
		$this->checkInstanceOf($entity);
		parent::set($key, $entity);
	}

	private function checkInstanceOf($entity)
	{
		if (!($entity instanceof $this->entityClass)) {
			throw new CollectionException(sprintf('Entity must be instance of %s.', $this->entityClass));
		}
	}

}
