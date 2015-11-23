<?php

namespace ShoPHP\Front;

use ShoPHP\Cart;
use ShoPHP\CartService;
use ShoPHP\Category;
use ShoPHP\CategoryService;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var CartService */
	private $cartService;

	/** @var Category */
	private $currentCategory;

	/** @var Cart|null */
	private $cart;

	public function injectFrontBase(
		CategoryService $categoryService,
		CartService $cartService
	)
	{
		$this->categoryService = $categoryService;
		$this->cartService = $cartService;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryService->getRoot();
		$this->template->currentCategory = $this->currentCategory;
		$this->template->cart = $this->cart;
	}

	protected function startup()
	{
		parent::startup();
		$this->cart = $this->cartService->getCurrentCart();
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

	protected function getCart()
	{
		return $this->cart;
	}

}
