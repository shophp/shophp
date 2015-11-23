<?php

namespace ShoPHP\Front\Product;

use Nette\Application\BadRequestException;
use ShoPHP\CartItem;
use ShoPHP\CartService;
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

	/** @var BuyFormFactory */
	private $buyFormFactory;

	/** @var CartService */
	private $cartService;

	public function __construct(
		ProductService $productService,
		CategoryService $categoryService,
		CartService $cartService,
		BuyFormFactory $buyFormFactory
	)
	{
		parent::__construct();
		$this->productService = $productService;
		$this->categoryService = $categoryService;
		$this->buyFormFactory = $buyFormFactory;
		$this->cartService = $cartService;
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
		$this->getCart()->addItem($item);

		if (!$form->hasErrors()) {
			$this->cartService->save($this->getCart());
			if ($item->getAmount() > 1) {
				$this->flashMessage(sprintf('%dx %s was added to cart.', $item->getAmount(), $this->product->getName()));
			} else {
				$this->flashMessage(sprintf('%s was added to cart.', $this->product->getName()));
			}
			$this->redirect('this');
		}
	}

}
