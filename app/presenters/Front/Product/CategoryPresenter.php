<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\CategoryService;

class CategoryPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	public function __construct(CategoryService $categoryService)
	{
		parent::__construct();
		$this->categoryService = $categoryService;
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

}
