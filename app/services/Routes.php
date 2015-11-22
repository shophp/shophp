<?php

namespace ShoPHP;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Routes extends RouteList
{

	/**
	 * @param string[][] $routes
	 */
	public function __construct(array $routes)
	{
		parent::__construct();

		foreach ($routes as $mask => $parameters) {
			$this[] = new Route($mask, array_key_exists('defaults', $parameters) ? $parameters['defaults'] : []);
		}
	}

}
