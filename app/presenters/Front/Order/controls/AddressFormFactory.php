<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Cart;

interface AddressFormFactory
{

	/**
	 * @param Cart $cart
	 * @return AddressForm
	 */
	function create(Cart $cart);

}
