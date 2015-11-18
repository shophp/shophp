<?php

namespace ShoPHP\Front\Home;

use Doctrine\ORM\EntityManagerInterface;

class HomepagePresenter extends \ShoPHP\Front\BasePresenter
{

	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		parent::__construct();
		$this->entityManager = $entityManager;
	}

	public function renderDefault()
	{

	}

	public function createComponentAddOffer()
	{
		$form = new Form;
		$form->addText('name', 'Vaše jméno:')
			->setRequired('Jméno je povinné.');
		$form->addText('email', 'E-mail:')
			->setRequired('Email je povinný.')
			->addRule(Form::EMAIL, 'Zadej validní email.');
		$form->addText('price', 'Vaše nabídka (Kč):')
			->setRequired('Cena je povinná.')
			->addRule(Form::RANGE, 'Zadej číslo.', [0, NULL]);

		$dql = "SELECT b.id, b.author, b.name, b.year FROM Book b";
		$books = $this->entityManager->createQuery($dql)->getResult();
		$bookOptions = [];
		foreach ($books as $book) {
			$bookOptions[$book['id']] = "$book[author]: $book[name] ($book[year])";
		}

		$form->addSelect('bookId', 'Kniha:', $bookOptions)
			->setPrompt('-')
			->setRequired('Výběr knihy je povinný.');

		$form->addSubmit('send', 'Nabídnout');

		$form->onSuccess[] = $this->addOffer;

		return $form;
	}

	public function addOffer(Form $form)
	{
		$values = $form->values;

		$offer = new Offer;
		$offer->setName($values->name);
		$offer->setEmail($values->email);
		$offer->setPrice($values->price);
		$offer->assignToBook($this->entityManager->find('Book', $values->bookId));
		$this->entityManager->persist($offer);
		$this->entityManager->flush();

		$this->flashMessage("Nabídka přidána.");
		$this->redirect('this');
	}

}
