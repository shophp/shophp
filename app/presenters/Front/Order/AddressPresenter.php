<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CartService;

class AddressPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var AddressFormFactory */
	private $addressFormFactory;

	/** @var CartService */
	private $cartService;

	public function __construct(AddressFormFactory $addressFormFactory, CartService $cartService)
	{
		parent::__construct();
		$this->addressFormFactory = $addressFormFactory;
		$this->cartService = $cartService;
	}

	public function actionDefault()
	{
	}

	public function createComponentAddressForm()
	{
		$form = $this->addressFormFactory->create($this->getCart());
		$form->onSuccess[] = function(AddressForm $form) {
			$this->updateAddress($form);
		};
		return $form;
	}

	private function updateAddress(AddressForm $form)
	{
		$values = $form->getValues();
		$this->getCart()->setAddress($values->name, $values->street, $values->city, $values->zip);

		if (!$form->hasErrors()) {
			$this->cartService->save($this->getCart());
			$this->redirect(':Front:Order:Payment:');
		}
	}

}
