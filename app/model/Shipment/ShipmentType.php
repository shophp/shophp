<?php

namespace ShoPHP\Shipment;

/**
 * @method static ShipmentType PERSONAL
 * @method static ShipmentType TRANSPORT_BY_COMPANY
 * @method static ShipmentType TO_COLLECTION_POINT
 */
class ShipmentType extends \ShoPHP\Enum
{

	const PERSONAL = 1;
	const TRANSPORT_BY_COMPANY = 2;
	const TO_COLLECTION_POINT = 3;

	public static function getLabels()
	{
		return [
			self::PERSONAL => 'Personal point',
			self::TRANSPORT_BY_COMPANY => 'Transport company',
			self::TO_COLLECTION_POINT => 'Collection point',
		];
	}

}
