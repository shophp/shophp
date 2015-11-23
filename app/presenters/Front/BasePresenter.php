<?php

namespace ShoPHP\Front;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use ShoPHP\CartService;
use ShoPHP\Category;
use ShoPHP\CategoryService;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var CartService */
	private $cartService;

	/** @var SessionSection */
	private $cartSession;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(
		CategoryService $categoryService,
		CartService $cartService,
		Session $session
	)
	{
		$this->categoryService = $categoryService;
		$this->cartService = $cartService;
		$this->cartSession = $session->getSection('cart');
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryService->getRoot();
		$this->template->currentCategory = $this->currentCategory;

		$cart = null;
		if ($this->cartSession->cartId !== null) {
			$cart = $this->cartService->getById($this->cartSession->cartId);
		}
		$this->template->cart = $cart;
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

}
