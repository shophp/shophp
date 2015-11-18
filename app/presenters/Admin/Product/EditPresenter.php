<?php

namespace ShoPHP\Admin\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Product;
use ShoPHP\Repository\ProductRepository;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormFactory */
	private $productFormFactory;

	/** @var ProductRepository */
	private $productRepository;

	/** @var Product */
	private $product;

	public function __construct(ProductFormFactory $productFormFactory, ProductRepository $productRepository)
	{
		parent::__construct();
		$this->productFormFactory = $productFormFactory;
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

	protected function createComponentProductForm()
	{
		$form = $this->productFormFactory->create('Update');
		$form->onSuccess[] = function(ProductForm $form) {
			$this->updateProduct($form);
		};
		return $form;
	}

	/**
	 * @return ProductForm
	 */
	private function getEditForm()
	{
		return $this->getComponent('productForm');
	}

	private function updateProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$this->product->setName($values->name);
		$this->product->setPrice($values->price);
		$this->product->setDescription($values->description);
		$this->product->setDiscountPercent($values->discount);
		$this->productRepository->flush();

		$this->flashMessage(sprintf('Product %s has been updated.', $this->product->getName()));
		$this->redirect('this');
	}

}
