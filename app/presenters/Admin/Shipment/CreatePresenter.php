<?php

namespace ShoPHP\Admin\Shipment;

use ShoPHP\EntityDuplicateException;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentService;
use ShoPHP\Shipment\ShipmentCollectionPoint;
use ShoPHP\Shipment\ShipmentTransportCompany;
use ShoPHP\Shipment\ShipmentType;

class CreatePresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ShipmentFormControlFactory */
	private $shipmentFormControlFactory;

	/** @var ShipmentService */
	private $shipmentService;

	public function __construct(ShipmentService $shipmentService, ShipmentFormControlFactory $shipmentFormControlFactory
	)
	{
		parent::__construct();
		$this->shipmentFormControlFactory = $shipmentFormControlFactory;
		$this->shipmentService = $shipmentService;
	}

	public function actionDefault()
	{
	}

	protected function createComponentShipmentFormControl()
	{
		$control = $this->shipmentFormControlFactory->create();
		$form = $control->getForm();
		$form->onSuccess[] = function(ShipmentForm $form) {
			$this->createShipment($form);
		};
		return $control;
	}

	private function createShipment(ShipmentForm $form)
	{
		$values = $form->getValues();

		$type = ShipmentType::createFromValue($values->type);
		switch ($type->getValue()) {
			case ShipmentType::PERSONAL:
				$shipmentOption = new ShipmentPersonalPoint(
					$values->address->name !== '' ? $values->address->name : null,
					$values->address->street,
					$values->address->city,
					$values->address->zip
				);
				if ($values->address->longitude !== '') {
					$shipmentOption->setGps($values->address->longitude, $values->address->latitude);
				}
				break;
			case ShipmentType::BY_TRANSPORT_COMPANY:
				$shipmentOption = new ShipmentTransportCompany($values->companyName, $values->price);
				if ($values->enableFreeFromCertainOrderPrice) {
					$shipmentOption->setMinimumOrderPriceToBeFree($values->minimumOrderPriceToBeFree);
				}
				break;
			case ShipmentType::TO_COLLECTION_POINT:
				$shipmentOption = new ShipmentCollectionPoint(
					$values->address->name !== '' ? $values->address->name : null,
					$values->address->street,
					$values->address->city,
					$values->address->zip,
					$values->price
				);
				if ($values->address->longitude !== '') {
					$shipmentOption->setGps($values->address->longitude, $values->address->latitude);
				}
				if ($values->enableFreeFromCertainOrderPrice) {
					$shipmentOption->setMinimumOrderPriceToBeFree($values->minimumOrderPriceToBeFree);
				}
				break;
			default:
				throw new \LogicException();
		}

		try {
			if (!$form->hasErrors()) {
				$this->shipmentService->create($shipmentOption);
				$this->flashMessage('Shipment has been created.');
				$this->redirect(':Admin:Shipment:List:');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Shipment company with name %s already exists.', $shipmentOption->getName()));
		}
	}

}
