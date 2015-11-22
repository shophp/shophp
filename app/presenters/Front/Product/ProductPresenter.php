<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Product;
use ShoPHP\Repository\CategoryRepository;
use ShoPHP\Repository\ProductRepository;

class ProductPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var ProductRepository */
	private $productRepository;

	/** @var Product */
	private $product;

	/** @var CategoryRepository */
	private $categoryRepository;

	public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
	{
		parent::__construct();
		$this->productRepository = $productRepository;
		$this->categoryRepository = $categoryRepository;
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
			$productCandidates = $this->productRepository->findByPath($productPath);
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
			throw new BadRequestException(sprintf('Product %s not found.', $path));
		}

		$this->setCurrentCategory($category);
	}

	public function renderDefault()
	{
		$this->template->product = $this->product;
	}

}
