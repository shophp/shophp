<?php

namespace ShoPHP\Front;

use ShoPHP\LoginFormFactory;
use ShoPHP\LogoutFormFactory;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\Product\Category;
use ShoPHP\Product\CategoryService;

abstract class BasePresenter extends \ShoPHP\BasePresenter
{

	/** @var CategoryService */
	private $categoryService;

	/** @var CurrentCartService */
	private $currentCartService;

	/** @var LoginFormFactory */
	private $loginFormFactory;

	/** @var LogoutFormFactory */
	private $logoutFormFactory;

	/** @var \Nette\Security\User */
	private $user;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(
		CategoryService $categoryService,
		CurrentCartService $currentCartService,
		LoginFormFactory $loginFormFactory,
		LogoutFormFactory $logoutFormFactory,
		\Nette\Security\User $user
	)
	{
		$this->categoryService = $categoryService;
		$this->currentCartService = $currentCartService;
		$this->loginFormFactory = $loginFormFactory;
		$this->logoutFormFactory = $logoutFormFactory;
		$this->user = $user;
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->categories = $this->categoryService->getRoot();
		$this->template->currentCategory = $this->currentCategory;
		$this->template->cart = $this->currentCartService->getCurrentCart();
		$this->template->user = $this->user;
	}

	protected function setCurrentCategory(Category $category)
	{
		$this->currentCategory = $category;
	}

	protected function createComponentLoginForm()
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function () {
			$this->redirect('this');
		};
		return $form;
	}

	protected function createComponentLogoutForm()
	{
		$form = $this->logoutFormFactory->create();
		$form->onSuccess[] = function () {
			$this->redirect('this');
		};
		return $form;
	}

}
