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

	/**
	 * @return ShipmentOption
	 */
	public function getById(ShipmentType $type, $id)
	{
		if ($type === ShipmentType::PERSONAL()) {
			return $this->personalPointRepository->find($id);

		} elseif ($type === ShipmentType::TRANSPORT_BY_COMPANY()) {
			return $this->transportCompanyRepository->find($id);

		} elseif ($type === ShipmentType::TRANSPORT_TO_BRAND()) {
			return $this->transportBrandRepository->find($id);

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
	 * @return ShipmentTransportBrand[]
	 */
	public function getTransportBrands()
	{
		return $this->transportBrandRepository->findAll();
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

