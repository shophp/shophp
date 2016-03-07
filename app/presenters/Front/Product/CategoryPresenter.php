<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Product\CategoryService;

class CategoryPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var string */
	private $imagesDir;

	public function __construct(CategoryService $categoryService, $imagesDir)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
		$this->imagesDir = $imagesDir;
	}

	/**
	 * @param string $path
	 */
	public function actionDefault($path)
	{
		$category = $this->categoryService->getByPath($path);
		if ($category === null) {
			// todo CategoryAlias
			throw new BadRequestException(sprintf('Category with path %s not found.', $path));
		}

		$this->setCurrentCategory($category);
	}

	public function renderDefault()
	{
		$this->template->imagesDir = $this->imagesDir;
	}

}
