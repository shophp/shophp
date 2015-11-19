<?php

namespace ShoPHP;

use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;
use Nette\Localization\ITranslator;

class BaseControl extends \Nette\Application\UI\Control
{

	/** @var ITranslator */
	private $translator;

	public function __construct(ITranslator $translator)
	{
		parent::__construct();
		$this->translator = $translator;
	}

	/**
	 * @return ITemplate
	 */
	protected function createTemplate()
	{
		$template = parent::createTemplate();
		if ($template instanceof Template) {
			$template->setTranslator($this->translator);
		}
		return $template;
	}

	/**
	 * @param string $name
	 * @return IComponent
	 */
	protected function createComponent($name)
	{
		$component = parent::createComponent($name);
		if ($component instanceof \Nette\Forms\Form) {
			$component->setTranslator($this->translator);
		}
		return $component;
	}

}
