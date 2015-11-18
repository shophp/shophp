<?php

namespace ShoPHP;

class BaseDataService extends \Nette\Object
{

	/** @var string */
	private $projectName;

	/**
	 * @param string $projectName
	 */
	public function __construct($projectName)
	{
		$this->projectName = $projectName;
	}

	/**
	 * @return string
	 */
	public function getProjectName()
	{
		return $this->projectName;
	}

}
