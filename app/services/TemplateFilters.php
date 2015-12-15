<?php

namespace ShoPHP;

class TemplateFilters extends \Nette\Object
{

	/** @var MoneyHelper */
	private $moneyHelper;

	public function __construct(MoneyHelper $moneyHelper)
	{
		$this->moneyHelper = $moneyHelper;
	}

	public function formatPrice($price)
	{
		return $this->moneyHelper->formatPrice($price);
	}

}
