<?php

namespace ShoPHP;

use Nette\Localization\ITranslator;
use Nette\Utils\Paginator;

class PaginatorControl extends \ShoPHP\BaseControl
{

	/** @var Paginator */
	private $paginator;

	/** @var int */
	private $threshold;

	/** @var string */
	private $pageParameter;

	public function __construct(
		ITranslator $translator,
		Paginator $paginator,
		$threshold = 3,
		$pageParameter = 'page'
	)
	{
		parent::__construct($translator);
		$this->paginator = $paginator;
		$this->threshold = $threshold;
		$this->pageParameter = $pageParameter;
	}

	public function render()
	{
		$startingPage = $this->paginator->getPage() - $this->threshold;
		$startingPage = $startingPage < 1 ? 1 : $startingPage;
		$this->template->startingPage = $startingPage;

		$closingPage = $this->paginator->getPage() + $this->threshold;
		$closingPage = $closingPage > $this->paginator->getPageCount() ? $this->paginator->getPageCount() : $closingPage;
		$this->template->closingPage = $closingPage;

		$this->template->hasUndisclosed = $startingPage !== 2 || $closingPage !== $this->paginator->getPageCount() - 1;

		$this->template->paginator = $this->paginator;
		$this->template->pageParameter = $this->pageParameter;
		$this->template->setFile(__DIR__ . '/PaginatorControl.latte');
		$this->template->render();
	}

}
