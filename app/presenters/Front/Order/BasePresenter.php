<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CurrentCartService;

class BasePresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var CurrentCartService */
	private $currentCartService;

	public function injectOrderBase(CurrentCartService $currentCartService)
	{
		$this->currentCartService = $currentCartService;
	}

	protected function startup()
	{
		parent::startup();
		if (!$this->currentCartService->getCurrentCart()->hasItems()) {
			$this->flashMessage('Yout cart is empty.');
			$this->redirect(':Front:Home:Homepage:');
		}
	}

}
