<?php

namespace ShoPHP\Order;

class OrderService extends \ShoPHP\EntityService
{

	public function create(Order $order)
	{
		$this->createEntity($order);
	}

}
