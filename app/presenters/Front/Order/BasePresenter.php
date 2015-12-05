<?php

namespace ShoPHP\Front\Order;

class BasePresenter extends \ShoPHP\Front\BasePresenter
{

	protected function startup()
	{
		parent::startup();
		if (!$this->getCart()->hasItems()) {
			$this->flashMessage('Yout cart is empty.');
			$this->redirect(':Front:Home:Homepage:');
		}
	}

}
