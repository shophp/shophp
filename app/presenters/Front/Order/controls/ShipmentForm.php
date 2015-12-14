<?php

namespace ShoPHP\Front\Order;

use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\TextInput;
use ShoPHP\Order\Shipment;
use ShoPHP\Order\ShipmentByTransportCompany;
use ShoPHP\Shipment\ShipmentService;
use ShoPHP\Shipment\ShipmentType;

class ShipmentForm extends \Nette\Application\UI\Form
{

	use \ShoPHP\AddressForm;

	/** @var ShipmentService */
	private $shipmentService;

	/** @var Shipment */
	private $shipment;

	public function __construct(ShipmentService $shipmentService, Shipment $shipment = null)
	{
		parent::__construct();
		$this->shipmentService = $shipmentService;
		$this->shipment = $shipment;

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
		$shipmentOptions = [];

		$personalPoints = $this->shipmentService->getPersonalPoints();
		foreach ($personalPoints as $personalPoint) {
			$key = sprintf('%d-%d', ShipmentType::PERSONAL, $personalPoint->getId());
			$shipmentOptions[$key] = sprintf('Personally at: %s', $personalPoint->getDescription());
		}

		$collectionPoints = $this->shipmentService->getCollectionPoints();
		foreach ($collectionPoints as $collectionPoint) {
			$key = sprintf('%d-%d', ShipmentType::TO_COLLECTION_POINT, $collectionPoint->getId());
			$shipmentOptions[$key] = sprintf('At collection point: %s', $collectionPoint->getDescription());
		}

		$transportCompanies = $this->shipmentService->getTransportCompanies();
		$transportCompanyKeys = [];
		foreach ($transportCompanies as $transportCompany) {
			$key = sprintf('%d-%d', ShipmentType::TRANSPORT_BY_COMPANY, $transportCompany->getId());
			$shipmentOptions[$key] = $transportCompany->getDescription();
			$transportCompanyKeys[] = $key;
		}

		$shipmentControl = $this->addRadioList('shipment', 'Shipment', $shipmentOptions)
			->setDefaultValue(key($shipmentOptions))
			->setRequired();

		if ($this->shipment !== null) {
			$shipmentControl->setDefaultValue(sprintf(
				'%d-%d',
				$this->shipment->getShipmentOption()->getType()->getValue(),
				$this->shipment->getShipmentOption()->getId()
			));
		}

		$defaultName = null;
		$defaultStreet = null;
		$defaultCity = null;
		$defaultZip = null;
		if ($this->shipment instanceof ShipmentByTransportCompany) {
			$defaultName = $this->shipment->getName();
			$defaultStreet = $this->shipment->getStreet();
			$defaultCity = $this->shipment->getCity();
			$defaultZip = $this->shipment->getZip();
		}
		$requiring = function (TextInput $control) use ($shipmentControl, $transportCompanyKeys) {
			return $control->addConditionOn($shipmentControl, self::IS_IN, $transportCompanyKeys);
		};
		$this->addNameControl('name', $defaultName, $requiring);
		$this->addStreetControl('street', $defaultStreet, $requiring);
		$this->addCityControl('city', $defaultCity, $requiring);
		$this->addZipControl('zip', $defaultZip, $requiring);

		$shipmentControl->addCondition(self::IS_IN, $transportCompanyKeys)
			->toggle('order-shipment-address');

		$this->addSubmit('next', 'Next');
	}

}
