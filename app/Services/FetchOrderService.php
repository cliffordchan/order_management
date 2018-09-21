<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

/**
 * Class FetchOrderService
 *
 * This service returns a list of orders based on the page and limit.
 * However, this does not provide sorting function
 *
 * @package App\Services
 */
class FetchOrderService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * FetchOrderService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 20);

        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });

        $orders = Order::select('id', 'distance', 'status')->paginate($limit);

        return $orders->toArray()['data'];
    }
}
