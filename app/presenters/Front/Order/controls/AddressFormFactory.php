<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Cart;

interface AddressFormFactory
{

	/**
	 * @param Cart $cart
	 * @return AddressForm
	 */
	function create(Cart $cart);

}
