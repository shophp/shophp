<?php

namespace ShoPHP\Admin\Product;

use Nette\Application\BadRequestException;
use ShoPHP\Product;
use ShoPHP\Repository\CategoryRepository;
use ShoPHP\Repository\ProductRepository;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductRepository */
	private $productRepository;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var Product */
	private $product;

	public function __construct(
		ProductFormControlFactory $productFormControlFactory,
		ProductRepository $productRepository,
		CategoryRepository $categoryRepository
	)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productRepository = $productRepository;
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id)
	{
		if ($id !== null) {
			$this->product = $this->productRepository->getById($id);
		}
		if ($this->product === null) {
			throw new BadRequestException(sprintf('Product %d not found.', $id));
		}
	}

	public function renderDefault()
	{
		$this->template->product = $this->product;
	}

	protected function createComponentProductFormControl()
	{
		$control = $this->productFormControlFactory->create($this->product);
		$form = $control->getForm();
		$form->onSuccess[] = function(ProductForm $form) {
			$this->updateProduct($form);
		};
		return $control;
	}

	private function updateProduct(ProductForm $form)
	{
		$values = $form->getValues();
		$this->product->setName($values->name);
		$this->product->setPrice($values->price);
		$this->product->setDescription($values->description);
		$this->product->setDiscountPercent($values->discount);
		$this->product->setCategories($this->categoryRepository->getByIds($values->categories));

		if (!$form->hasErrors()) {
			$this->productRepository->flush();

			$this->flashMessage(sprintf('Product %s has been updated.', $this->product->getName()));
			$this->redirect('this');
		}
	}

}
