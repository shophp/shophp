<?php

namespace ShoPHP\Admin\Order;

use Nette\Utils\Paginator;
use ShoPHP\Order\Order;
use ShoPHP\Order\OrderService;
use ShoPHP\PaginatorControlFactory;

class ListPresenter extends \ShoPHP\Admin\BasePresenter
{

	const ORDERS_PER_PAGE = 20;

	/** @var OrderService */
	private $orderService;

	/** @var PaginatorControlFactory */
	private $paginatorControlFactory;

	/** @var Order[] */
	private $orders;

	/** @var Paginator */
	private $paginator;

	public function __construct(
		OrderService $orderService,
		PaginatorControlFactory $paginatorControlFactory
	)
	{
		parent::__construct();
		$this->orderService = $orderService;
		$this->paginatorControlFactory = $paginatorControlFactory;
	}

	public function actionDefault($page = 1)
	{
		if ($page < 1) {
			$this->redirect('this', ['page' => 1]);
		}

		$this->paginator = new Paginator();
		$this->paginator->setItemsPerPage(self::ORDERS_PER_PAGE);
		$this->paginator->setPage($page);
		$orders = $this->orderService->getAll($this->paginator->getItemsPerPage(), $this->paginator->getOffset());
		$this->paginator->setItemCount(count($orders));

		$orders = iterator_to_array($orders);
		if (count($orders) === 0 && $page > 1) {
			$this->redirect('this', ['page' => 1]);
		}

		$this->orders = $orders;
	}

	public function renderDefault()
	{
		$this->template->orders = $this->orders;
	}

	protected function createComponentPaginator()
	{
		return $this->paginatorControlFactory->create($this->paginator);
	}

}
