<?php

namespace ShoPHP\Front;

use Nette\Security\AuthenticationException;
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

	/** @var \Nette\Security\User */
	private $user;

	/** @var Category */
	private $currentCategory;

	public function injectFrontBase(
		CategoryService $categoryService,
		CurrentCartService $currentCartService,
		LoginFormFactory $loginFormFactory,
		\Nette\Security\User $user
	)
	{
		$this->categoryService = $categoryService;
		$this->currentCartService = $currentCartService;
		$this->loginFormFactory = $loginFormFactory;
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

		$form->onSuccess[] = function (LoginForm $form) {
			$values = $form->getValues();
			try {
				$this->user->login($values->email, $values->password);
				$this->redirect('this');
			} catch (AuthenticationException $e) {
				$this->flashMessage('Invalid credentials.', 'fail');
			}
		};

		return $form;
	}

}
