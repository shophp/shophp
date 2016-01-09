<?php

namespace ShoPHP\Front\Order;

use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\TextInput;
use ShoPHP\Order\Shipment;
use ShoPHP\Order\ShipmentByTransportCompany;
use ShoPHP\Shipment\ShipmentHelper;
use ShoPHP\Shipment\ShipmentService;
use ShoPHP\Shipment\ShipmentType;
use ShoPHP\User\User;

class ShipmentForm extends \Nette\Application\UI\Form
{

	use \ShoPHP\AddressForm;

	/** @var ShipmentService */
	private $shipmentService;

	/** @var ShipmentHelper */
	private $shipmentHelper;

	/** @var Shipment */
	private $shipment;

	/** @var User */
	private $user;

	public function __construct(
		ShipmentService $shipmentService,
		ShipmentHelper $shipmentHelper,
		Shipment $shipment = null,
		User $user = null
	)
	{
		parent::__construct();
		$this->shipmentService = $shipmentService;
		$this->shipmentHelper = $shipmentHelper;
		$this->shipment = $shipment;
		$this->user = $user;

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
			$shipmentOptions[$key] = sprintf('Personally at: %s', $this->shipmentHelper->formatShipmentOption($personalPoint));
		}

		$collectionPoints = $this->shipmentService->getCollectionPoints();
		foreach ($collectionPoints as $collectionPoint) {
			$key = sprintf('%d-%d', ShipmentType::TO_COLLECTION_POINT, $collectionPoint->getId());
			$shipmentOptions[$key] = sprintf('At collection point: %s', $this->shipmentHelper->formatShipmentOption($collectionPoint));
		}

		$transportCompanies = $this->shipmentService->getTransportCompanies();
		$transportCompanyKeys = [];
		foreach ($transportCompanies as $transportCompany) {
			$key = sprintf('%d-%d', ShipmentType::BY_TRANSPORT_COMPANY, $transportCompany->getId());
			$shipmentOptions[$key] = $this->shipmentHelper->formatShipmentOption($transportCompany);
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
		} elseif ($this->user !== null) {
			$defaultName = $this->user->getName();
			$defaultStreet = $this->user->getStreet();
			$defaultCity = $this->user->getCity();
			$defaultZip = $this->user->getZip();
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
