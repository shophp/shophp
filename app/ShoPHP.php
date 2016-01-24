<?php

namespace ShoPHP;

use Nette\Configurator;

class ShoPHP extends \Nette\Object
{

	public static function initialize(Configurator $configurator)
	{
		$configurator->addConfig(__DIR__ . '/config/base.neon');
		$configurator->addConfig(__DIR__ . '/config/routes.neon');
		$configurator->addConfig(__DIR__ . '/config/services.neon');
		$configurator->addConfig(__DIR__ . '/config/presenters.neon');
	}

}
