<?php

namespace ShoPHP\Order;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\Session;
use Nette\Http\SessionSection;
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

	/** @var SessionSection */
	private $cartSession;

	public function __construct(EntityManagerInterface $entityManager, Session $session)
	{
		parent::__construct($entityManager);
		$this->cartRepository = $entityManager->getRepository(Cart::class);
		$this->itemRepository = $entityManager->getRepository(CartItem::class);
		$this->cartSession = $session->getSection('cart');
	}

	public function getCurrentCart()
	{
		if ($this->cartSession->cartId !== null) {
			$cart = $this->cartRepository->find($this->cartSession->cartId);
			if ($cart !== null) {
				return $cart;
			}
		}
		return new Cart();
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

	public function save(Cart $cart)
	{
		if (count($cart->getItems()) > 0) {
			if ($cart->getId() === null) {
				$this->createEntity($cart);
				$this->cartSession->cartId = $cart->getId();
			} else {
				$this->updateEntity($cart);
			}
		}
	}

}
