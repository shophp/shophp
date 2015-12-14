<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CartService;

class PaymentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var ShipmentFormFactory */
	private $shipmentFormFactory;

	/** @var CartService */
	private $cartService;

	public function __construct(ShipmentFormFactory $shipmentFormFactory, CartService $cartService)
	{
		parent::__construct();
		$this->shipmentFormFactory = $shipmentFormFactory;
		$this->cartService = $cartService;
	}

	public function actionDefault()
	{
	}

}
