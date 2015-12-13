<?php

namespace ShoPHP\Admin\Shipment;

use Nette\Localization\ITranslator;
use ShoPHP\Shipment\ShipmentOption;

class ShipmentFormControl extends \ShoPHP\BaseControl
{

	/** @var ShipmentFormFactory */
	private $shipmentFormFactory;

	/** @var ShipmentOption|null */
	private $editedShipment;

	public function __construct(
		ShipmentFormFactory $shipmentFormFactory,
		ITranslator $translator,
		ShipmentOption $editedShipment = null
	)
	{
		parent::__construct($translator);
		$this->shipmentFormFactory = $shipmentFormFactory;
		$this->editedShipment = $editedShipment;
	}

	/**
	 * @return ShipmentForm
	 */
	public function getForm()
	{
		return $this->getComponent('shipmentForm');
	}

	protected function createComponentShipmentForm()
	{
		return $this->shipmentFormFactory->create($this->editedShipment);
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/ShipmentFormControl.latte');
		$this->template->render();
	}

}
