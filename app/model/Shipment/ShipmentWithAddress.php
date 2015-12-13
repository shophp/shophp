<?php

namespace ShoPHP\Shipment;

use ShoPHP\EntityInvalidArgumentException;

trait ShipmentWithAddress
{

	/** @Column(type="string", nullable=true) */
	protected $name;

	/** @Column(type="string") */
	protected $street;

	/** @Column(type="string") */
	protected $city;

	/** @Column(type="string") */
	protected $zip;

	/** @Column(type="float", nullable=true) */
	protected $longitude;

	/** @Column(type="float", nullable=true) */
	protected $latitude;

	public function hasName()
	{
		return $this->name !== null;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function getZip()
	{
		return $this->zip;
	}

	public function getDescription()
	{
		$description = sprintf('%s %s %s', $this->getStreet(),  $this->getCity(), $this->getZip());
		if ($this->hasName()) {
			$description = sprintf('%s, %s', $this->getName(), $description);
		}
		return $description;
	}

	public function hasGps()
	{
		return $this->latitude !== null;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * @param string|null $name
	 * @param string $street
	 * @param string $city
	 * @param string $zip
	 */
	public function setAddress($name, $street, $city, $zip)
	{
		$name = $name !== null ? (string) $name : null;
		$street = (string) $street;
		$city = (string) $city;
		$zip = (string) $zip;

		if ($name === '') {
			throw new EntityInvalidArgumentException('Name can be NULL but cannot be empty.');
		}
		if ($street === '') {
			throw new EntityInvalidArgumentException('Street cannot be empty.');
		}
		if ($city === '') {
			throw new EntityInvalidArgumentException('City cannot be empty.');
		}
		if ($zip === '') {
			throw new EntityInvalidArgumentException('Zip cannot be empty.');
		}

		$this->name = $name;
		$this->street = $street;
		$this->city = $city;
		$this->zip = $zip;
	}

	public function setGps($longitude, $latitude)
	{
		$longitude = (float) $longitude;
		$latitude = (float) $latitude;

		if ($longitude >= 180 || $longitude <= -180) {
			throw new EntityInvalidArgumentException('Invalid longitude %f.', $latitude);
		}
		if ($latitude >= 90 || $latitude <= -90) {
			throw new EntityInvalidArgumentException('Invalid latitude %f.', $latitude);
		}

		$this->longitude = (float) $longitude;
		$this->latitude = (float) $latitude;
	}

	public function removeGps()
	{
		$this->longitude = null;
		$this->latitude = null;
	}

}
