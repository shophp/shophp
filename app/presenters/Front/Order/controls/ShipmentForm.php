<?php

namespace ShoPHP\Front\Order;

use Nette\Forms\Controls\RadioList;
use ShoPHP\Shipment\ShipmentService;
use ShoPHP\Shipment\ShipmentType;

class ShipmentForm extends \Nette\Application\UI\Form
{

	/** @var ShipmentService */
	private $shipmentService;

	public function __construct(ShipmentService $shipmentService)
	{
		parent::__construct();
		$this->shipmentService = $shipmentService;
		$this->createFields();
	}

	public function getChosenShipment()
	{
		/** @var RadioList $control */
		$control = $this->getComponent('shipment');
		list($shipmentType, $shipmentId) = explode('-', $control->getValue());
		$shipmentType = ShipmentType::createFromValue((int) $shipmentType);
		return $this->shipmentService->getById($shipmentType, (int) $shipmentId);
	}

	private function createFields()
	{
		$this->addShipmentControl();
		$this->addSubmit('next', 'Next');
	}

	private function addShipmentControl()
	{
		$shipmentOptions = [];

		$personalPoints = $this->shipmentService->getPersonalPoints();
		foreach ($personalPoints as $personalPoint) {
			$key = sprintf('%d-%d', ShipmentType::PERSONAL, $personalPoint->getId());
			$shipmentOptions[$key] = sprintf('Personally at: %s', $personalPoint->getDescription());
		}

		$transportBrands = $this->shipmentService->getTransportBrands();
		foreach ($transportBrands as $transportBrand) {
			$key = sprintf('%d-%d', ShipmentType::TRANSPORT_TO_BRAND, $transportBrand->getId());
			$shipmentOptions[$key] = sprintf('At collection point: %s', $transportBrand->getDescription());
		}

		$transportCompanies = $this->shipmentService->getTransportCompanies();
		foreach ($transportCompanies as $transportCompany) {
			$key = sprintf('%d-%d', ShipmentType::TRANSPORT_BY_COMPANY, $transportCompany->getId());
			$shipmentOptions[$key] = $transportCompany->getDescription();
		}

		$this->addRadioList('shipment', 'Shipment', $shipmentOptions)
			->setDefaultValue(key($shipmentOptions))
			->setRequired();
	}

}
