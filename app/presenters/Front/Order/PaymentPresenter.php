<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CurrentCartService;
use ShoPHP\Order\Order;
use ShoPHP\Order\OrderService;

class PaymentPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var OrderService */
	private $orderService;

	/** @var CurrentCartService */
	private $currentCartService;

	public function __construct(OrderService $orderService, CurrentCartService $currentCartService)
	{
		parent::__construct();
		$this->orderService = $orderService;
		$this->currentCartService = $currentCartService;
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
		$order = new Order($this->currentCartService->getCurrentCart());
		$this->orderService->create($order);
		$this->currentCartService->resetCurrentCart();
		$this->flashMessage('Ordered !');
		$this->redirect(':Front:Order:Order:');
	}

}
