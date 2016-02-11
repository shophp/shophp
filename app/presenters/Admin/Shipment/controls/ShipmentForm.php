<?php

namespace ShoPHP\Admin\Shipment;

use Nette\Forms\Controls\ChoiceControl;
use Nette\Forms\Controls\TextInput;
use ShoPHP\AddressFormContainer;
use ShoPHP\Shipment\ShipmentOption;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentCollectionPoint;
use ShoPHP\Shipment\ShipmentTransportCompany;
use ShoPHP\Shipment\ShipmentType;

class ShipmentForm extends \Nette\Application\UI\Form
{

	/** @var ShipmentOption|null */
	private $editedShipment;

	public function __construct(ShipmentOption $editedShipment = null)
	{
		parent::__construct();
		$this->editedShipment = $editedShipment;
		$this->createFields();
	}

	private function createFields()
	{
		if ($this->editedShipment === null) {
			$typeControl = $this->addTypeControl();
			$this->addAddressControls(null, $typeControl);
			$this->addCompanyNameControl(null, $typeControl);
			$this->addPriceControl(null, $typeControl);
			$this->addMinimumOrderPriceToBeFreeControl(null, $typeControl);

		} elseif ($this->editedShipment instanceof ShipmentPersonalPoint) {
			$this->addAddressControls($this->editedShipment);

		} elseif ($this->editedShipment instanceof ShipmentCollectionPoint) {
			$this->addAddressControls($this->editedShipment);
			$this->addPriceControl($this->editedShipment);
			$this->addMinimumOrderPriceToBeFreeControl($this->editedShipment);

		} elseif ($this->editedShipment instanceof ShipmentTransportCompany) {
			$this->addCompanyNameControl($this->editedShipment);
			$this->addPriceControl($this->editedShipment);
			$this->addMinimumOrderPriceToBeFreeControl($this->editedShipment);
		}

		$this->addSubmit('send', $this->editedShipment !== null ? 'Update' : 'Create');
	}

	private function addTypeControl()
	{
		$control = $this->addRadioList('type', 'Type', ShipmentType::getLabels());
		$control->setDefaultValue(ShipmentType::PERSONAL);
		return $control;
	}

	/**
	 * @param ShipmentPersonalPoint|ShipmentCollectionPoint|null $shipment
	 */
	private function addAddressControls(ShipmentOption $shipment = null, ChoiceControl $typeControl = null)
	{
		$addressContainer = new AddressFormContainer();
		$this->addComponent($addressContainer, 'address');

		$defaultName = null;
		$defaultStreet = null;
		$defaultCity = null;
		$defaultZip = null;
		$defaultLongitude = null;
		$defaultLatitude = null;
		if ($shipment !== null) {
			$defaultName = $shipment->getName();
			$defaultStreet = $shipment->getStreet();
			$defaultCity = $shipment->getCity();
			$defaultZip = $shipment->getZip();
			$defaultLongitude = $shipment->getLongitude();
			$defaultLatitude = $shipment->getLatitude();
		}

		$addressContainer->addText('name', 'Name')
			->setDefaultValue($defaultName);

		$requiring = true;
		if ($typeControl !== null) {
			$requiring = function (TextInput $control) use ($typeControl) {
				return $control->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::BY_TRANSPORT_COMPANY);
			};

			$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::BY_TRANSPORT_COMPANY)
				->toggle('shipment-address');
		}

		$addressContainer->addStreetControl('street', $defaultStreet, $requiring);
		$addressContainer->addCityControl('city', $defaultCity, $requiring);
		$addressContainer->addZipControl('zip', $defaultZip, $requiring);
		$addressContainer->addGpsControls('longitude', 'latitude', $defaultLongitude, $defaultLatitude);
	}

	private function addCompanyNameControl(ShipmentTransportCompany $shipment = null, ChoiceControl $typeControl = null)
	{
		$control = $this->addText('companyName', 'Company name');

		if ($typeControl === null) {
			$requiredCondition = $control;
			$control->setDefaultValue($shipment->getName());

		} else {
			$requiredCondition = $control->addConditionOn($typeControl, self::EQUAL, ShipmentType::BY_TRANSPORT_COMPANY);
			$typeControl->addCondition(self::EQUAL, ShipmentType::BY_TRANSPORT_COMPANY)
				->toggle('shipment-company-name');
		}

		$requiredCondition->setRequired('Please fill company name.');

		return $control;
	}

	/**
	 * @param ShipmentTransportCompany|ShipmentCollectionPoint|null $shipment
	 */
	private function addPriceControl(ShipmentOption $shipment = null, ChoiceControl $typeControl = null)
	{
		$errorMessage = 'Price must be positive number.';
		$priceControl = $this->addText('price', 'Price');
		$priceControl->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue(0)
			->addRule(self::FLOAT, $errorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() >= 0;
			}, $errorMessage);

		if ($typeControl === null) {
			$requiredCondition = $priceControl;
			$priceControl->setDefaultValue($shipment->getPrice());

		} else {
			$requiredCondition = $priceControl->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::PERSONAL);
			$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::PERSONAL)
				->toggle('shipment-price');
		}

		$requiredCondition->setRequired('Please fill price.');
	}

	/**
	 * @param ShipmentTransportCompany|ShipmentCollectionPoint|null $shipment
	 */
	private function addMinimumOrderPriceToBeFreeControl(ShipmentOption $shipment = null, ChoiceControl $typeControl = null)
	{
		$enableControl = $this->addCheckbox('enableFreeFromCertainOrderPrice', 'Free from certain order price');
		$enableControl->addCondition(self::EQUAL, true)
			->toggle('shipment-free-price-input');

		$errorMessage = 'Minimum order price must be positive number.';
		$priceControl = $this->addText('minimumOrderPriceToBeFree', 'Minimum order price to be free');
		$priceControl->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue(0);

		if ($typeControl === null) {
			$requiredCondition = $priceControl;
			$enableControl->setDefaultValue($shipment->isFreeFromCertainOrderPrice());
			if ($shipment->isFreeFromCertainOrderPrice()) {
				$priceControl->setDefaultValue($shipment->getMinimumOrderPriceToBeFree());
			}

		} else {
			$requiredCondition = $priceControl
				->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::PERSONAL);

			$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::PERSONAL)
				->toggle('shipment-free-price');
		}

		$requiredCondition
			->addConditionOn($enableControl, self::EQUAL, true)
				->setRequired()
				->addRule(self::FLOAT, $errorMessage)
				->addRule(function (TextInput $input) {
					return $input->getValue() > 0;
				}, $errorMessage);
	}

}
