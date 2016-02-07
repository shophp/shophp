<?php

namespace ShoPHP;

use Nette\Utils\Paginator;

interface PaginatorControlFactory
{

	/**
	 * @param Paginator $paginator
	 * @return PaginatorControl
	 */
	function create(Paginator $paginator);

}
