<?php

namespace ShoPHP\Front;

use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;
use Nette\Localization\ITranslator;
use ShoPHP\BaseDataService;
use ShoPHP\Category;
use ShoPHP\Repository\CategoryRepository;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{

	/** @var BaseDataService */
	private $baseDataService;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var ITranslator */
	private $translator;

	/** @var Category */
	private $currentCategory;

	public function injectBase(
		BaseDataService $baseDataService,
		CategoryRepository $categoryRepository,
		ITranslator $translator
	)
	{
		$this->baseDataService = $baseDataService;
		$this->categoryRepository = $categoryRepository;
		$this->translator = $translator;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->projectName = $this->baseDataService->getProjectName();
		$this->template->categories = $this->categoryRepository->getRoot();
		$this->template->currentCategory = $this->currentCategory;
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

	/**
	 * @return string[]
	 */
	public function formatLayoutTemplateFiles()
	{
		return [
			sprintf('%s/layout.latte', __DIR__),
		];
	}

	/**
	 * @return string[]
	 */
	public function formatTemplateFiles()
	{
		$presenter = substr($this->getName(), strrpos($this->getName(), ':') + 1);
		$dir = dirname($this->getReflection()->getFileName());
		return [
			sprintf("%s/%s.%s.latte", $dir, $presenter, $this->view),
		];
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
