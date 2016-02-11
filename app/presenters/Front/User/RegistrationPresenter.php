<?php

namespace ShoPHP\Front\User;

use ShoPHP\EntityDuplicateException;
use ShoPHP\User\User;
use ShoPHP\User\UserService;

class RegistrationPresenter extends \ShoPHP\Front\BasePresenter
{

	/** @var UserService */
	private $userService;

	/** @var RegistrationFormFactory */
	private $registrationFormFactory;

	/** @var \Nette\Security\User */
	private $user;

	/** @var string|null */
	private $backlink;

	public function __construct(
		UserService $userService,
		RegistrationFormFactory $registrationFormFactory,
		\Nette\Security\User $user
	)
	{
		parent::__construct();
		$this->userService = $userService;
		$this->registrationFormFactory = $registrationFormFactory;
		$this->user = $user;
	}

	/**
	 * @param string|null $backlink
	 */
	public function actionDefault($backlink = null)
	{
		if ($this->user->isLoggedIn()) {
			$this->restoreRequest($backlink);
			$this->redirect(':Front:Home:Homepage:');
		}
		$this->backlink = $backlink;
	}

	public function createComponentRegistrationForm()
	{
		$form = $this->registrationFormFactory->create();
		$form->onSuccess[] = function(RegistrationForm $form) {
			$this->registerUser($form);
		};
		return $form;
	}

	private function registerUser(RegistrationForm $form)
	{
		$values = $form->getValues();
		$user = new User($values->email, $values->password);
		$user->setName($values->address->name);
		$user->setStreet($values->address->street);
		$user->setCity($values->address->city);
		$user->setZip($values->address->zip);

		try {
			if (!$form->hasErrors()) {
				$this->userService->create($user);
				$this->flashMessage('Account has been created.');
				$this->user->login($user->getEmail(), $values->password);
				$this->restoreRequest($this->backlink);
				$this->redirect(':Front:Home:Homepage:');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('User with e-mail %s already exists.', $user->getEmail()));
		}
	}

}
