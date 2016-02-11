<?php

namespace ShoPHP\Admin\Shipment;

use Nette\Application\BadRequestException;
use ShoPHP\EntityDuplicateException;
use ShoPHP\Shipment\ShipmentOption;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentService;
use ShoPHP\Shipment\ShipmentCollectionPoint;
use ShoPHP\Shipment\ShipmentTransportCompany;
use ShoPHP\Shipment\ShipmentType;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ShipmentFormControlFactory */
	private $shipmentFormControlFactory;

	/** @var ShipmentService */
	private $shipmentService;

	/** @var ShipmentOption */
	private $shipment;

	public function __construct(ShipmentService $shipmentService, ShipmentFormControlFactory $shipmentFormControlFactory
	)
	{
		parent::__construct();
		$this->shipmentFormControlFactory = $shipmentFormControlFactory;
		$this->shipmentService = $shipmentService;
	}

	public function actionDefault($id, $type)
	{
		if ($id !== null) {
			$type = (int) $type;
			if (!ShipmentType::isValidValue($type)) {
				throw new BadRequestException(sprintf('Unknown shipment type %d.', $type));
			}
			$this->shipment = $this->shipmentService->getById(ShipmentType::createFromValue($type), $id);
		}
		if ($this->shipment === null) {
			throw new BadRequestException(sprintf('Shipment with ID %d not found.', $id));
		}
	}

	public function renderDefault()
	{
		$this->template->shipment = $this->shipment;
	}

	protected function createComponentShipmentFormControl()
	{
		$control = $this->shipmentFormControlFactory->create($this->shipment);
		$form = $control->getForm();
		$form->onSuccess[] = function(ShipmentForm $form) {
			$this->updateShipment($form);
		};
		return $control;
	}

	private function updateShipment(ShipmentForm $form)
	{
		$values = $form->getValues();

		if ($this->shipment instanceof ShipmentPersonalPoint || $this->shipment instanceof ShipmentCollectionPoint) {
			$this->shipment->setAddress(
				$values->address->name !== '' ? $values->address->name : null,
				$values->address->street,
				$values->address->city,
				$values->address->zip
			);
			if ($values->address->longitude !== '') {
				$this->shipment->setGps($values->address->longitude, $values->address->latitude);
			} else {
				$this->shipment->removeGps();
			}
		}

		if ($this->shipment instanceof ShipmentTransportCompany || $this->shipment instanceof ShipmentCollectionPoint) {
			$this->shipment->setPrice($values->price);
			if ($values->enableFreeFromCertainOrderPrice) {
				$this->shipment->setMinimumOrderPriceToBeFree($values->minimumOrderPriceToBeFree);
			} else {
				$this->shipment->eraseToBeFreeFromCertainOrderPrice();
			}
		}

		if ($this->shipment instanceof ShipmentTransportCompany) {
			$this->shipment->setName($values->companyName);
		}

		try {
			if (!$form->hasErrors()) {
				$this->shipmentService->update($this->shipment);
				$this->flashMessage('Shipment has been updated.');
				$this->redirect('this');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Shipment company with name %s already exists.', $this->shipment));
		}
	}

}
