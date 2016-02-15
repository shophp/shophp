<?php

namespace ShoPHP\Front\Order;

interface PaymentFormFactory
{

	/**
	 * @return PaymentForm
	 */
	function create();

}
