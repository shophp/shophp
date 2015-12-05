<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Cart;

interface CartFormFactory
{

	/**
	 * @param Cart $cart
	 * @return CartForm
	 */
	function create(Cart $cart);

}
