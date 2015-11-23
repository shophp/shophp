<?php

namespace ShoPHP;

class Translator extends \Nette\Object implements \Nette\Localization\ITranslator
{

	/**
	 * @param string $message
	 * @param int|null $count
	 * @return string
	 */
	function translate($message, $count = null)
	{
		$args = array_slice(func_get_args(), 1);
		if (count($args) > 0) {
			return vsprintf($message, $args);
		}
		return $message;
	}

}
