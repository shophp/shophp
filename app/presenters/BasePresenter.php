<?php

namespace ShoPHP;

use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;
use Nette\Localization\ITranslator;
use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{

	/** @var BaseDataService */
	private $baseDataService;

	/** @var CategoryService */
	private $categoryService;

	/** @var TemplateFilters */
	private $templateFilters;

	/** @var ITranslator */
	private $translator;

	/** @var Category */
	private $currentCategory;

	public function injectBase(
		BaseDataService $baseDataService,
		CategoryService $categoryService,
		TemplateFilters $templateFilters,
		ITranslator $translator
	)
	{
		$this->baseDataService = $baseDataService;
		$this->categoryService = $categoryService;
		$this->templateFilters = $templateFilters;
		$this->translator = $translator;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->projectName = $this->baseDataService->getProjectName();
		$this->template->categories = $this->categoryService->getRoot();
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
		$module = substr($this->getName(), 0, strpos($this->getName(), ':'));
		return [
			sprintf('%s/%s/layout.latte', __DIR__, $module),
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

			$template->addFilter(null, function ($filter, ...$args) {
				if (method_exists($this->templateFilters, $filter)) {
					return $this->templateFilters->$filter(...$args);
				}
				return null;
			});
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
