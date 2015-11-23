<?php

namespace ShoPHP\Front\Order;

class CartPresenter extends \ShoPHP\Front\BasePresenter
{

	public function actionDefault()
	{
		if (!$this->getCart()->hasItems()) {
			$this->flashMessage('Yout cart is empty.');
			$this->redirect(':Front:Home:Homepage:');
		}
	}

}
