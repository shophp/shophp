<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\CategoryService;
use ShoPHP\Product;
use ShoPHP\ProductService;

class ProductPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var ProductService */
	private $productService;

	/** @var Product */
	private $product;

	/** @var CategoryService */
	private $categoryService;

	public function __construct(ProductService $productService, CategoryService $categoryService)
	{
		parent::__construct();
		$this->productService = $productService;
		$this->categoryService = $categoryService;
	}

	/**
	 * @param string $path
	 */
	public function actionDefault($path)
	{
		$pathsSeparatorPosition = strrpos($path, '/');
		if ($pathsSeparatorPosition !== false) {
			$categoryPath = substr($path, 0, $pathsSeparatorPosition);
			$productPath = substr($path, $pathsSeparatorPosition + 1);
			$productCandidates = $this->productService->getByPath($productPath);
			foreach ($productCandidates as $productCandidate) {
				foreach ($productCandidate->getCategories() as $category) {
					if ($category->getPath() === $categoryPath) {
						$this->product = $productCandidate;
						break 2;
					}
				}
			}
		}
		if ($this->product === null) {
			throw new BadRequestException(sprintf('Product with path %s not found.', $path));
		}

		$this->setCurrentCategory($category);
	}

	public function renderDefault()
	{
		$this->template->product = $this->product;
	}

}
