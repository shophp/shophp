<?php

namespace ShoPHP;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory extends \Nette\Object
{

	/** @var string[][] */
	private $routes;

	/**
	 * @param string[][] $routes
	 */
	public function __construct(array $routes)
	{
		$this->routes = $routes;
	}

	/**
	 * @return IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		foreach ($this->routes as $mask => $parameters) {
			if (!array_key_exists('defaults', $parameters)) {
				throw new \InvalidArgumentException(sprintf('Missing "%s" route defaults parameter.', $mask));
			}
			$router[] = new Route($mask, $parameters['defaults']);
		}
		return $router;
	}

}
