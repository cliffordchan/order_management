<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderAlreadyBeenTakenException;
use App\Exceptions\OrderCancelledException;
use App\Exceptions\OrderCompletedException;
use App\Exceptions\OrderNotFoundException;
use App\Handlers\CreateOrderHandler;
use App\Handlers\UpdateOrderHandler;
use App\Libraries\DistanceCalculator as DistanceCalculator;
use App\Services\FetchOrderService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends BaseController
{
    /**
     * @var DistanceCalculator
     */
    private $distanceCalculator;

    /**
     * OrderController constructor.
     * @param DistanceCalculator $distanceCalculator
     */
    public function __construct(DistanceCalculator $distanceCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\DistanceUnavailableException
     */
    public function createOrder(Request $request)
    {
        $handler = new CreateOrderHandler($request, $this->distanceCalculator);

        return response()->json($handler->execute());
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws OrderAlreadyBeenTakenException
     * @throws OrderCancelledException
     * @throws OrderCompletedException
     * @throws OrderNotFoundException
     */
    public function updateOrder(Request $request, $id)
    {
        $handler = new UpdateOrderHandler($request);

        return response()->json($handler->execute($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listOrders(Request $request)
    {
        $service = new FetchOrderService($request);

        return response()->json($service->execute());
    }
}
