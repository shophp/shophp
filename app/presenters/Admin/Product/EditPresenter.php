<?php

namespace ShoPHP\Admin\Product;

use Nette\Application\BadRequestException;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;
use ShoPHP\Product\CategoryService;
use ShoPHP\EntityDuplicateException;
use ShoPHP\Product\Product;
use ShoPHP\Product\ProductImageService;
use ShoPHP\Product\ProductService;

class EditPresenter extends \ShoPHP\Admin\BasePresenter
{

	/** @var ProductFormControlFactory */
	private $productFormControlFactory;

	/** @var ProductService */
	private $productService;

	/** @var ProductImageService */
	private $productImageService;

	/** @var CategoryService */
	private $categoryService;

	/** @var Product */
	private $product;

	public function __construct(
		ProductFormControlFactory $productFormControlFactory,
		ProductService $productService,
		ProductImageService $productImageService,
		CategoryService $categoryService
	)
	{
		parent::__construct();
		$this->productFormControlFactory = $productFormControlFactory;
		$this->productService = $productService;
		$this->productImageService = $productImageService;
		$this->categoryService = $categoryService;
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id)
	{
		if ($id !== null) {
			$this->product = $this->productService->getById($id);
		}
		if ($this->product === null) {
			throw new BadRequestException(sprintf('Product with ID %d not found.', $id));
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
		$this->product->setOriginalPrice($values->price);
		$this->product->setDescription($values->description);
		if ($values->discountType === ProductForm::DISCOUNT_PERCENT) {
			$this->product->setDiscountPercent($values->discountPercent);
		} else {
			$this->product->setNominalDiscount($values->nominalDiscount);
		}
		$this->product->setCategories($this->categoryService->getByIds($values->categories));

		if ($this->product->hasImages()) {
			$fixedOrders = $this->fixOrders($values->images);
			foreach ($values->images as $imageId => $imageData) {
				$imageId = (int)$imageId;
				$image = $this->productImageService->getById($imageId);
				if ((bool)$imageData->remove) {
					$this->productImageService->delete($image);
				} else {
					$image->setDescription($imageData->description === '' ? null : $imageData->description);
					$image->setOrder($fixedOrders[$imageId]);
				}
			}
		}

		/** @var FileUpload $fileUpload */
		foreach ($values->imagesUpload as $fileUpload) {
			$this->productImageService->create($this->product, $fileUpload);
		}

		try {
			if (!$form->hasErrors()) {
				$this->productService->update($this->product);

				$this->flashMessage(sprintf('Product %s has been updated.', $this->product->getName()));
				$this->redirect('this');
			}
		} catch (EntityDuplicateException $e) {
			$form->addError(sprintf('Product with name %s already exists.', $this->product->getName()));
		}
	}

	private function fixOrders(ArrayHash $imagesData)
	{
		$fixedOrders = iterator_to_array($imagesData);
		uasort($fixedOrders, function ($imageDataA, $imageDataB) {
			$removeA = (bool) $imageDataA->remove;
			$removeB = (bool) $imageDataB->remove;
			if ($removeA && $removeB) {
				return 0;
			}
			if ($removeA) {
				return 1;
			}
			if ($removeB) {
				return -1;
			}
			$orderA = (int) $imageDataA->order;
			$orderB = (int) $imageDataB->order;
			return $orderA === $orderB ? 1 : ($orderA > $orderB ? 1 : -1);
		});

		$sequence = 1;
		foreach ($fixedOrders as & $order) {
			if ((bool) $order->remove) {
				break;
			}
			$order = $sequence++;
		}

		return $fixedOrders;
	}

}
