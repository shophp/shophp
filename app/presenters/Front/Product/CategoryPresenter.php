<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Repository\CategoryRepository;

class CategoryPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var CategoryRepository */
	private $categoryRepository;

	public function __construct(CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * @param string $path
	 */
	public function actionDefault($path)
	{
		$category = $this->categoryRepository->findByPath($path);
		if ($category === null) {
			// todo CategoryAlias
			throw new BadRequestException(sprintf('Category with path %s not found.', $path));
		}

		$this->setCurrentCategory($category);
	}

}
