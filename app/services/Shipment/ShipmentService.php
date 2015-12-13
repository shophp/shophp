<?php

namespace ShoPHP\Shipment;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\EntityDuplicateException;

class ShipmentService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $personalPointRepository;

	/** @var ObjectRepository */
	private $transportCompanyRepository;

	/** @var ObjectRepository */
	private $transportBrandRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->personalPointRepository = $entityManager->getRepository(ShipmentPersonalPoint::class);
		$this->transportCompanyRepository = $entityManager->getRepository(ShipmentTransportCompany::class);
		$this->transportBrandRepository = $entityManager->getRepository(ShipmentTransportBrand::class);
	}

	public function create(ShipmentOption $shipment)
	{
		$this->checkDuplicity($shipment);
		$this->createEntity($shipment);
	}

	public function update(ShipmentOption $shipment)
	{
		$this->updateEntity($shipment);
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

