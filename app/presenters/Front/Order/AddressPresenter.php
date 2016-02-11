<?php

namespace ShoPHP\Front\Order;

use ShoPHP\Order\CurrentCartService;

class AddressPresenter extends \ShoPHP\Front\Order\BasePresenter
{

	/** @var AddressFormFactory */
	private $addressFormFactory;

	/** @var CurrentCartService */
	private $currentCartService;

	public function __construct(AddressFormFactory $addressFormFactory, CurrentCartService $currentCartService)
	{
		parent::__construct();
		$this->addressFormFactory = $addressFormFactory;
		$this->currentCartService = $currentCartService;
	}

	public function actionDefault()
	{
	}

	public function createComponentAddressForm()
	{
		$form = $this->addressFormFactory->create($this->currentCartService->getCurrentCart());
		$form->onSuccess[] = function(AddressForm $form) {
			$this->updateAddress($form);
		};
		return $form;
	}

	private function updateAddress(AddressForm $form)
	{
		$values = $form->getValues();
		$this->currentCartService->getCurrentCart()->setAddress($values->address->name, $values->address->street, $values->address->city, $values->address->zip);

		if (!$form->hasErrors()) {
			$this->currentCartService->saveCurrentCart();
			$this->redirect(':Front:Order:Payment:');
		}
	}

}
