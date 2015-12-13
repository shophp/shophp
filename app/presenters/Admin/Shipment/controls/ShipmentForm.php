<?php

namespace ShoPHP\Admin\Shipment;

use Nette\Forms\Controls\ChoiceControl;
use Nette\Forms\Controls\TextInput;
use ShoPHP\AddressForm;
use ShoPHP\Shipment\ShipmentOption;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentCollectionPoint;
use ShoPHP\Shipment\ShipmentTransportCompany;
use ShoPHP\Shipment\ShipmentType;

class ShipmentForm extends \Nette\Application\UI\Form
{

	use AddressForm;

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
		$typeControl = $this->addTypeControl();
		$this->addAddressControls($typeControl);
		$this->addCompanyNameControl($typeControl);
		$this->addPriceControl($typeControl);
		$this->addMinimumOrderPriceToBeFreeControl($typeControl);

		$this->addSubmit('send', $this->editedShipment !== null ? 'Update' : 'Create');
	}

	private function addTypeControl()
	{
		$control = $this->addRadioList('type', 'Type', ShipmentType::getLabels());
		$defaultValue = ShipmentType::PERSONAL;
		if ($this->editedShipment !== null) {
			$defaultValue = $this->editedShipment->getType()->getValue();
		}
		$control->setDefaultValue($defaultValue);

		return $control;
	}

	private function addAddressControls(ChoiceControl $typeControl)
	{
		$defaultName = null;
		$defaultStreet = null;
		$defaultCity = null;
		$defaultZip = null;
		$defaultLongitude = null;
		$defaultLatitude = null;
		if (
			$this->editedShipment !== null
			&& ($this->editedShipment instanceof ShipmentPersonalPoint || $this->editedShipment instanceof ShipmentTransportCompany)
		) {
			$defaultName = $this->editedShipment->getName();
			$defaultStreet = $this->editedShipment->getStreet();
			$defaultCity = $this->editedShipment->getCity();
			$defaultZip = $this->editedShipment->getZip();
			$defaultLongitude = $this->editedShipment->getLongitude();
			$defaultLatitude = $this->editedShipment->getLatitude();
		}

		$this->addText('name', 'Name')
			->setDefaultValue($defaultName);

		$requiring = function (TextInput $control) use ($typeControl) {
			return $control->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::TRANSPORT_BY_COMPANY);
		};
		$this->addStreetControl('street', $defaultStreet, $requiring);
		$this->addCityControl('city', $defaultCity, $requiring);
		$this->addZipControl('zip', $defaultZip, $requiring);
		$this->addGpsControls('longitude', 'latitude', $defaultLongitude, $defaultLatitude);

		$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::TRANSPORT_BY_COMPANY)
			->toggle('shipment-address');
	}

	private function addCompanyNameControl(ChoiceControl $typeControl)
	{
		$control = $this->addText('companyName', 'Company name')
			->addConditionOn($typeControl, self::EQUAL, ShipmentType::TRANSPORT_BY_COMPANY)
			->setRequired('Please fill company name.');
		$typeControl->addCondition(self::EQUAL, ShipmentType::TRANSPORT_BY_COMPANY)
			->toggle('shipment-company-name');
		return $control;
	}

	private function addPriceControl(ChoiceControl $typeControl)
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

		$priceControl->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::PERSONAL)
			->setRequired();

		$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::PERSONAL)
			->toggle('shipment-price');

		if (
			$this->editedShipment !== null
			&& ($this->editedShipment instanceof ShipmentTransportCompany || $this->editedShipment instanceof ShipmentCollectionPoint)
		) {
			$priceControl->setDefaultValue($this->editedShipment->getPrice());
		}
	}

	private function addMinimumOrderPriceToBeFreeControl(ChoiceControl $typeControl)
	{
		$enableControl = $this->addCheckbox('enableFreeFromCertainOrderPrice', 'Free from certain order price');
		$enableControl->addCondition(self::EQUAL, true)
			->toggle('shipment-free-price-input');

		$errorMessage = 'Price must be positive number.';
		$priceControl = $this->addText('minimumOrderPriceToBeFree', 'Minimum order price to be free');
		$priceControl->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue(0);
		$priceControl
			->addConditionOn($enableControl, self::EQUAL, true)
			->addConditionOn($typeControl, self::NOT_EQUAL, ShipmentType::PERSONAL)
			->setRequired()
			->addRule(self::FLOAT, $errorMessage)
			->addRule(function (TextInput $input) {
				return $input->getValue() > 0;
			}, $errorMessage);

		if (
			$this->editedShipment !== null
			&& ($this->editedShipment instanceof ShipmentTransportCompany || $this->editedShipment instanceof ShipmentCollectionPoint)
			&& $this->editedShipment->isFreeFromCertainOrderPrice()
		) {
			$priceControl->setDefaultValue($this->editedShipment->getMinimumOrderPriceToBeFree());
		}

		$typeControl->addCondition(self::NOT_EQUAL, ShipmentType::PERSONAL)
			->toggle('shipment-free-price');
	}

}
