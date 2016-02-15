<?php

namespace ShoPHP\Payment;

/**
 * @method static PaymentType CASH
 * @method static PaymentType BANK
 * @method static PaymentType CARD
 */
class PaymentType extends \ShoPHP\Enum
{

	const CASH = 1;
	const BANK = 2;
	const CARD = 3;

	public static function getLabels()
	{
		return [
			self::CASH => 'Cash',
			self::BANK => 'Bank transfer',
			self::CARD => 'Card transfer',
		];
	}

	public function isCash()
	{
		return $this->getValue() === self::CASH;
	}

	public function isBankTransfer()
	{
		return $this->getValue() === self::BANK;
	}

	public function isCardTransfer()
	{
		return $this->getValue() === self::CARD;
	}

}
