<?php

namespace ShoPHP;

use ReflectionClass;

abstract class Enum implements LabeledEnum
{

	private static $constants = [];

	private static $instances = [];

	private $value;

	private function __construct($value)
	{
		if (!self::isValidValue($value)) {
			throw new \UnexpectedValueException(sprintf('Unexpected value %s for %s enum.', $value, get_called_class()));
		}
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getLabel()
	{
		return self::getLabels()[$this->value];
	}

	public static function __callStatic($name, $args)
	{
		if (!self::isValidName($name)) {
			throw new \UnexpectedValueException(sprintf('Unexpected name %s for %s enum.', $name, get_called_class()));
		}
		return self::createFromValue(constant(sprintf('%s::%s', get_called_class(), $name)));
	}

	/**
	 * @param integer $value
	 * @return static
	 */
	public static function createFromValue($value)
	{
		$class = get_called_class();
		if (!isset(self::$instances[$class][$value])) {
			self::$instances[$class][$value] = new static($value);
		}
		return self::$instances[$class][$value];
	}

	public static function getConstants()
	{
		$class = get_called_class();
		if (!isset(self::$constants[$class])) {
			$reflect = new ReflectionClass($class);
			$constants = $reflect->getConstants();
			self::checkConsistence($constants);
			self::$constants[$class] = $constants;
		}
		return self::$constants[$class];
	}

	public static function isValidName($name)
	{
		$constants = self::getConstants();
		return array_key_exists($name, $constants);
	}

	public static function isValidValue($value)
	{
		return in_array($value, self::getConstants(), true);
	}

	private static function checkConsistence($constants)
	{
		if (count(array_unique($constants)) !== count($constants)) {
			throw new InvalidEnumException(sprintf('Enum %s has duplicate values.', get_called_class()));
		}
		$labels = static::getLabels();
		if (count($labels) !== count($constants) || count(array_diff($constants, array_keys($labels))) > 0) {
			throw new InvalidEnumException(sprintf('Enum %s has inconsistent labels.', get_called_class()));
		}
	}

}
