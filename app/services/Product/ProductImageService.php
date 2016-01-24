<?php

namespace ShoPHP\Product;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Http\FileUpload;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use ShoPHP\EntityInvalidArgumentException;

class ProductImageService extends \ShoPHP\EntityService
{

	/** @var ObjectRepository */
	private $repository;

	/** @var string */
	private $imagesDir;

	public function __construct(EntityManagerInterface $entityManager, $imagesDir)
	{
		parent::__construct($entityManager);
		$this->repository = $entityManager->getRepository(ProductImage::class);
		$this->imagesDir = $imagesDir;
	}

	/**
	 * @param integer $id
	 * @return ProductImage|null
	 */
	public function getById($id)
	{
		return $this->repository->find($id);
	}

	public function create(Product $product, FileUpload $fileUpload)
	{
		switch ($fileUpload->getContentType()) {
			case 'image/jpeg':
				$suffix = 'jpg';
				break;
			case 'image/png':
				$suffix = 'png';
				break;
			case 'image/gif':
				$suffix = 'gif';
				break;
			default:
				throw new EntityInvalidArgumentException(sprintf('File is of an unknown type %s.', $fileUpload->getContentType()));
		}

		$baseName = sprintf(
			'%s-%%s.%s',
			Strings::webalize($product->getName()),
			$suffix
		);
		do {
			$fileName = sprintf($baseName, Random::generate(5, '0-9a-zA-Z'));
			$path = sprintf('%s/%s', $this->imagesDir, $fileName);
		} while (file_exists($path));

		$fileUpload->move($path);
		$image = new ProductImage($product, $fileName);
		$this->createEntity($image);
		$product->addImage($image);
		return $image;
	}

	public function delete(ProductImage $image)
	{
		unlink(sprintf('%s/%s', $this->imagesDir, $image->getPath()));
		$this->removeEntity($image);
	}

}
