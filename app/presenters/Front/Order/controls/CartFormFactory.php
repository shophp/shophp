<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Cart;

interface CartFormFactory
{

	/**
	 * @param Cart $cart
	 * @return CartForm
	 */
	function create(Cart $cart);

}
