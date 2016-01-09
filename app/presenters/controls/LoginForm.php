<?php

namespace ShoPHP;

use Nette\Security\AuthenticationException;
use ShoPHP\Order\CurrentCartService;
use ShoPHP\User\User;

class LoginForm extends \Nette\Application\UI\Form
{

	/** * @var CurrentCartService */
	private $currentCartService;

	/** @var \Nette\Security\User*/
	private $user;

	/**
	 * @param string $permanentLoginExpiration
	 */
	public function __construct(
		CurrentCartService $currentCartService,
		\Nette\Security\User $user,
		$permanentLoginExpiration
	)
	{
		parent::__construct();
		$this->currentCartService = $currentCartService;
		$this->user = $user;

		$this->createFields();
		$this->addEventListeners($permanentLoginExpiration);
	}

	private function createFields()
	{
		$this->addEmailControl();
		$this->addPasswordControl();
		$this->addPermanentControl();
		$this->addSubmit('login', 'Login');
	}

	private function addEmailControl()
	{
		$control = $this->addText('email', 'E-mail');
		$control->setRequired();
		return $control;
	}

	private function addPasswordControl()
	{
		$control = $this->addPassword('password', 'Password');
		$control->setRequired();
		return $control;
	}

	private function addPermanentControl()
	{
		$this->addCheckbox('permanent', 'Do not logout');
	}

	private function addEventListeners($permanentLoginExpiration)
	{
		$this->onValidate[] = function () {
			$values = $this->getValues();
			try {
				$this->user->login($values->email, $values->password);
				$currentCart = $this->currentCartService->getCurrentCart();
				/** @var User $identity */
				$identity = $this->user->getIdentity();
				if ($currentCart->hasItems()) {
					$this->currentCartService->getCurrentCart()->setUser($identity);
					$this->currentCartService->saveCurrentCart();
				} elseif ($identity->hasAnyCart()) {
					$this->currentCartService->setCurrentCart($identity->getLastCart());
				}

			} catch (AuthenticationException $e) {
				$this->addError('Invalid credentials.');
			}
		};

		$this->onSuccess[] = function () use ($permanentLoginExpiration) {
			$values = $this->getValues();

			if ($values->permanent) {
				$this->user->setExpiration($permanentLoginExpiration, false);
			} else {
				$this->user->setExpiration(0, true);
			}
		};
	}

}
