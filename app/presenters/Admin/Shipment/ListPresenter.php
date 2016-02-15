<?php

namespace ShoPHP\Admin\Shipment;

use ShoPHP\Shipment\ShipmentService;

class ListPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ShipmentService */
	private $shipmentService;

	public function __construct(ShipmentService $shipmentService)
	{
		parent::__construct();
		$this->shipmentService = $shipmentService;
	}

	public function actionDefault()
	{
	}

	public function renderDefault()
	{
		$this->template->personalPoints = $this->shipmentService->getPersonalPoints();
		$this->template->transportCompanies = $this->shipmentService->getTransportCompanies();
		$this->template->collectionPoints = $this->shipmentService->getCollectionPoints();
	}

}
