<?php

namespace ShoPHP;

use Nette\Forms\Controls\TextInput;

trait AddressForm
{

	use RequiredFormFieldResolution;

	abstract protected function addText($name, $label = null, $cols = null, $maxLength = null);

	/**
	 * @param bool|callable $required
	 */
	protected function addNameControl($name, $defaultValue = null, $required = false)
	{
		/** @var TextInput $control */
		$control = $this->addText($name, 'Your name');
		$control->setDefaultValue($defaultValue);
		$this->resolveRequiring($control, 'Please fill the name.', $required);
		return $control;
	}

	/**
	 * @param bool|callable $required
	 */
	protected function addStreetControl($name, $defaultValue = null, $required = false)
	{
		/** @var TextInput $control */
		$control = $this->addText($name, 'Street and house number');
		$control->setDefaultValue($defaultValue);
		$this->resolveRequiring($control, 'Please fill the street.', $required);
		return $control;
	}

	/**
	 * @param bool|callable $required
	 */
	protected function addCityControl($name, $defaultValue = null, $required = false)
	{
		/** @var TextInput $control */
		$control = $this->addText($name, 'City');
		$control->setDefaultValue($defaultValue);
		$this->resolveRequiring($control, 'Please fill the city.', $required);
		return $control;
	}

	/**
	 * @param bool|callable $required
	 */
	protected function addZipControl($name, $defaultValue = null, $required = false)
	{
		/** @var TextInput $control */
		$control = $this->addText($name, 'Zip');
		$control->setDefaultValue($defaultValue);
		$this->resolveRequiring($control, 'Please fill the zip code.', $required);
		return $control;
	}

	/**
	 * @return TextInput[]
	 */
	protected function addGpsControls($longitudeName, $latitudeName, $defaultLongitude = null, $defaultLatitude = null, $required = false)
	{
		/** @var TextInput $longitudeControl */
		$errorMessage = 'Longitude must be number from -180 to 180.';
		$longitudeControl = $this->addText($longitudeName, 'Longitude');
		$longitudeControl->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue($defaultLongitude)
			->addCondition(self::FILLED)
				->addRule(self::FLOAT, $errorMessage)
				->addRule(self::RANGE, $errorMessage, [-180, 180]);

		$this->resolveRequiring($longitudeControl, 'Please fill longitude.', $required);

		/** @var TextInput $latitudeControl */
		$errorMessage = 'Latitude must be number from -90 to 90.';
		$latitudeControl = $this->addText($latitudeName, 'Latitude');
		$latitudeControl->setType('number')
			->setAttribute('step', 'any')
			->setDefaultValue($defaultLatitude)
			->addCondition(self::FILLED)
				->addRule(self::FLOAT, $errorMessage)
				->addRule(self::RANGE, $errorMessage, [-90, 90]);

		$this->resolveRequiring($latitudeControl, 'Please fill latitude.', $required);

		$errorMessage = 'Please fill both longitude and latitude.';
		$longitudeControl->addConditionOn($latitudeControl, self::FILLED)
			->addRule(self::FILLED, $errorMessage);
		$latitudeControl->addConditionOn($longitudeControl, self::FILLED)
			->addRule(self::FILLED, $errorMessage);

		return [$longitudeControl, $latitudeControl];
	}

}
