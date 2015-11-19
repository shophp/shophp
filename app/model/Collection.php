<?php

namespace ShoPHP;

abstract class Collection extends \Doctrine\Common\Collections\ArrayCollection
{

	/** @var string */
	private $entityClass;

	public function __construct()
	{
		parent::__construct();
		$this->entityClass = (string) $this->getEntityClass();
		if (!class_exists($this->entityClass)) {
			throw new CollectionException(sprintf('Class %s does not exist.', $this->entityClass));
		}
	}

	/**
	 * @return string
	 */
	abstract protected function getEntityClass();

	public function add($entity)
	{
		if (!($entity instanceof $this->entityClass)) {
			throw new CollectionException(sprintf('Entity added to collection must be instance of %s.', $this->entityClass));
		}
		return parent::add($entity);
	}

	public function set($key, $entity)
	{
		if (!($entity instanceof $this->entityClass)) {
			throw new CollectionException(sprintf('Entity set to collection must be instance of %s.', $this->entityClass));
		}
		parent::set($key, $entity);
	}

}
