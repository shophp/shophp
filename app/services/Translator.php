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
		return $message;
	}

}
