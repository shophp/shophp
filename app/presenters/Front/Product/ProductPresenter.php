<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Order\CartItem;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\Product\CategoryService;
use ShoPHP\Product\Product;
use ShoPHP\Product\ProductService;

class ProductPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var ProductService */
	private $productService;

	/** @var Product */
	private $product;

	/** @var CategoryService */
	private $categoryService;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var BuyFormFactory */
	private $buyFormFactory;

	/** @var string */
	private $imagesDir;

	public function __construct(
		ProductService $productService,
		CategoryService $categoryService,
		CurrentCartService $currentCartService,
		BuyFormFactory $buyFormFactory,
		$imagesDir
	)
	{
		parent::__construct();
		$this->productService = $productService;
		$this->categoryService = $categoryService;
		$this->currentCartService = $currentCartService;
		$this->buyFormFactory = $buyFormFactory;
		$this->imagesDir = $imagesDir;
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
		$this->template->currentProduct = $this->product;
		$this->template->imagesDir = $this->imagesDir;
	}

	protected function createComponentBuyForm()
	{
		$form = $this->buyFormFactory->create();
		$form->onSuccess[] = function(BuyForm $form) {
			$this->addProductToCart($form);
		};
		return $form;
	}

	private function addProductToCart(BuyForm $form)
	{
		$values = $form->getValues();
		$item = new CartItem($this->product, $values->amount);
		$this->currentCartService->getCurrentCart()->addItem($item);

		if (!$form->hasErrors()) {
			$this->currentCartService->saveCurrentCart();
			if ($item->getAmount() > 1) {
				$this->flashMessage(sprintf('%dx %s was added to cart.', $item->getAmount(), $this->product->getName()));
			} else {
				$this->flashMessage(sprintf('%s was added to cart.', $this->product->getName()));
			}
			$this->redirect('this');
		}
	}

}
