<?php

namespace ShoPHP;

class TemplateFilters extends \Nette\Object
{

	/** @var StringHelper */
	private $stringHelper;

	/** @var string */
	private $currency;

	/** @var bool */
	private $currencyWrittenAfter;

	/**
	 * @param string $currency
	 * @param bool $currencyWrittenAfter
	 */
	public function __construct(
		StringHelper $stringHelper,
		$currency,
		$currencyWrittenAfter
	)
	{
		$this->stringHelper = $stringHelper;
		$this->currency = $currency;
		$this->currencyWrittenAfter = $currencyWrittenAfter;
	}

	public function formatPrice($price)
	{
		$price = round($price, 2);
		if ($this->currencyWrittenAfter) {
			$formattedPrice = sprintf('%s %s', $price, $this->currency);
		} else {
			$formattedPrice = sprintf('%s %s', $this->currency, $price);
		}
		return $this->stringHelper->makeSpacesNonBreakable($formattedPrice);
	}

}
