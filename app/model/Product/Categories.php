<?php

namespace ShoPHP\Product;

class Categories extends \ShoPHP\Collection
{

	protected function getEntityClass()
	{
		return Category::class;
	}

}
