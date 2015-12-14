<?php

namespace ShoPHP\Shipment;

/**
 * @method static ShipmentType PERSONAL
 * @method static ShipmentType BY_TRANSPORT_COMPANY
 * @method static ShipmentType TO_COLLECTION_POINT
 */
class ShipmentType extends \ShoPHP\Enum
{

	const PERSONAL = 1;
	const BY_TRANSPORT_COMPANY = 2;
	const TO_COLLECTION_POINT = 3;

	public static function getLabels()
	{
		return [
			self::PERSONAL => 'Personal point',
			self::BY_TRANSPORT_COMPANY => 'Transport company',
			self::TO_COLLECTION_POINT => 'Collection point',
		];
	}

	public function isPersonal()
	{
		return $this->getValue() === self::PERSONAL;
	}

	public function isByTransportCompany()
	{
		return $this->getValue() === self::BY_TRANSPORT_COMPANY;
	}

	public function isToCollectionPoint()
	{
		return $this->getValue() === self::TO_COLLECTION_POINT;
	}

}
