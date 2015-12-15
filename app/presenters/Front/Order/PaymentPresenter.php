<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\Order;
use ShoPHP\Order\OrderService;

class PaymentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var OrderService */
	private $orderService;

	public function __construct(OrderService $orderService)
	{
		parent::__construct();
		$this->orderService = $orderService;
	}

	public function actionDefault()
	{
	}

	protected function createComponentOrderForm()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addSubmit('order', 'Order');
		$form->onSuccess[] = function (\Nette\Application\UI\Form $form) {
			$this->createOrder($form);
		};
		return $form;
	}

	private function createOrder(\Nette\Application\UI\Form $form)
	{
		$order = new Order($this->getCart());
		$this->orderService->create($order);
		$this->flashMessage('Ordered !');
	}

}
