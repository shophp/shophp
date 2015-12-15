<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShoPHP\Shipment\ShipmentCollectionPoint;
use ShoPHP\Shipment\ShipmentOption;
use ShoPHP\Shipment\ShipmentPersonalPoint;
use ShoPHP\Shipment\ShipmentTransportCompany;

class CartService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $cartRepository;

	/** @var ObjectRepository */
	private $itemRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->cartRepository = $entityManager->getRepository(Cart::class);
		$this->itemRepository = $entityManager->getRepository(CartItem::class);
	}

	/**
	 * @return Cart
	 */
	public function getById($id)
	{
		return $this->cartRepository->find($id);
	}

	/**
	 * @param int $id
	 * @return CartItem|null
	 */
	public function getItemById($id)
	{
		return $this->itemRepository->find($id);
	}

	public function removeItemFromCart(CartItem $item)
	{
		$this->removeEntity($item);
	}

	public function createShipmentForCart(Cart $cart, ShipmentOption $shipmentOption, $name = null, $street = null, $city = null, $zip = null)
	{
		if ($shipmentOption instanceof ShipmentPersonalPoint) {
			$shipment = new ShipmentPersonal($cart, $shipmentOption);
		} elseif ($shipmentOption instanceof ShipmentTransportCompany) {
			$shipment = new ShipmentByTransportCompany($cart, $shipmentOption, $name, $street, $city, $zip);
		} elseif ($shipmentOption instanceof ShipmentCollectionPoint) {
			$shipment = new ShipmentToCollectionPoint($cart, $shipmentOption);
		} else {
			throw new \LogicException();
		}

		if ($cart->hasShipment()) {
			if ($cart->getShipment()->equals($shipment)) {
				return;
			}
			$this->removeEntity($cart->getShipment());
		}

		$cart->setShipment($shipment);
		$this->createEntity($shipment);
	}

	public function create(Cart $cart)
	{
		$this->createEntity($cart);
	}

	public function update(Cart $cart)
	{
		$this->updateEntity($cart);
	}

}
