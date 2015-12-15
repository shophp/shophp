<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Order;
use ShoPHP\Order\OrderService;

class OrderPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var OrderService */
	private $orderService;

	/** @var Order */
	private $lastOrder;

	public function __construct(OrderService $orderService)
	{
		parent::__construct();
		$this->orderService = $orderService;
	}

	public function actionDefault()
	{
		$this->lastOrder = $this->orderService->getLastOrder();
		if ($this->lastOrder === null) {
			$this->redirect(':Front:Home:Homepage:');
		}
	}

	public function renderDefault()
	{
		$this->template->order = $this->lastOrder;
	}

}
