<?php

namespace ShoPHP\Admin\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Product;
use ShoPHP\Repository\ProductRepository;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductRepository */
	private $productRepository;

	/** @var Product */
	private $product;

	public function __construct(ProductFormControlFactory $productFormControlFactory, ProductRepository $productRepository)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productRepository = $productRepository;
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id)
	{
		$this->product = $this->productRepository->getById($id);
		if ($this->product === null) {
			throw new BadRequestException(sprintf('Product %d not found.', $id));
		}

		$form = $this->getEditForm();
		$form->setDefaults([
			'name' => $this->product->getName(),
			'description' => $this->product->getDescription(),
			'price' => $this->product->getPrice(),
			'discount' => $this->product->getDiscountPercent(),
		]);
	}

	public function renderDefault()
	{
		$this->template->product = $this->product;
	}

	protected function createComponentProductFormControl()
	{
		$control = $this->productFormControlFactory->create('Update');
		$form = $control->getForm();
		$form->onSuccess[] = function(ProductForm $form) {
			$this->updateProduct($form);
		};
		return $control;
	}

	/**
	 * @return ProductForm
	 */
	private function getEditForm()
	{
		return $this->getComponent('productFormControl')->getForm();
	}

	private function updateProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$this->product->setName($values->name);
		$this->product->setPrice($values->price);
		$this->product->setDescription($values->description);
		$this->product->setDiscountPercent($values->discount);
		$this->product->setCategories($form->getCategories());

		if (!$form->hasErrors()) {
			$this->productRepository->flush();

			$this->flashMessage(sprintf('Product %s has been updated.', $this->product->getName()));
			$this->redirect('this');
		}
	}

}
