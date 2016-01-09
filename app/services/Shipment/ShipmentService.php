<?php

namespace ShoPHP\Shipment;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\EntityDuplicateException;

class ShipmentService extends \ShoPHP\EntityService
{

	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var ObjectRepository */
	private $personalPointRepository;

	/** @var ObjectRepository */
	private $transportCompanyRepository;

	/** @var ObjectRepository */
	private $collectionPointRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->entityManager = $entityManager;
		$this->personalPointRepository = $entityManager->getRepository(ShipmentPersonalPoint::class);
		$this->transportCompanyRepository = $entityManager->getRepository(ShipmentTransportCompany::class);
		$this->collectionPointRepository = $entityManager->getRepository(ShipmentCollectionPoint::class);
	}

	public function create(ShipmentOption $shipment)
	{
		$this->checkDuplicity($shipment);
		$this->createEntity($shipment);
	}

	public function update(ShipmentOption $shipment)
	{
		$this->checkDuplicity($shipment);
		$this->updateEntity($shipment);
	}

	public function existsAnyShipmentOption()
	{
		$query = $this->entityManager->createQuery('SELECT 1 FROM ShoPHP\\Shipment\\ShipmentPersonalPoint');
		$query->setMaxResults(1);
		if (count($query->getResult()) > 0) {
			return true;
		}

		$query = $this->entityManager->createQuery('SELECT 1 FROM ShoPHP\\Shipment\\ShipmentCollectionPoint');
		$query->setMaxResults(1);
		if (count($query->getResult()) > 0) {
			return true;
		}

		$query = $this->entityManager->createQuery('SELECT 1 FROM ShoPHP\\Shipment\\ShipmentTransportCompany');
		$query->setMaxResults(1);
		if (count($query->getResult()) > 0) {
			return true;
		}

		return false;
	}

	/**
	 * @return ShipmentOption
	 */
	public function getById(ShipmentType $type, $id)
	{
		if ($type->isPersonal()) {
			return $this->personalPointRepository->find($id);

		} elseif ($type->isByTransportCompany()) {
			return $this->transportCompanyRepository->find($id);

		} elseif ($type->isToCollectionPoint()) {
			return $this->collectionPointRepository->find($id);

		} else {
			throw new \LogicException();
		}
	}

	/**
	 * @return ShipmentPersonalPoint[]
	 */
	public function getPersonalPoints()
	{
		return $this->personalPointRepository->findAll();
	}

	/**
	 * @return ShipmentTransportCompany[]
	 */
	public function getTransportCompanies()
	{
		return $this->transportCompanyRepository->findAll();
	}

	/**
	 * @return ShipmentCollectionPoint[]
	 */
	public function getCollectionPoints()
	{
		return $this->collectionPointRepository->findAll();
	}

	private function checkDuplicity(ShipmentOption $shipment)
	{
		if ($shipment instanceof ShipmentTransportCompany) {
			$duplicate = $this->transportCompanyRepository->findOneBy([
				'name' => $shipment->getName(),
			]);
			if ($duplicate !== null) {
				throw new EntityDuplicateException(sprintf('Shipment company with name %s already exists.', $shipment->getName()));
			}
		}
	}

}

