<?php

namespace ShoPHP;

class StringHelper extends \Nette\Object
{

	public function makeSpacesNonBreakable($string)
	{
		return str_replace(' ', "\xc2\xa0", $string);
	}

}
