<?php

namespace App\Handlers;

use App\Exceptions\OrderAlreadyBeenTakenException;
use App\Exceptions\OrderCancelledException;
use App\Exceptions\OrderCompletedException;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class UpdateOrderHandler
 * @package App\Handlers
 */
class UpdateOrderHandler
{
    const STATUS_TAKEN = 'taken';
    const STATUS_MESSAGE_SUCCESS = 'SUCCESS';
    const ERROR_ORDER_COMPLETED = 'ORDER_COMPLETED';
    const ERROR_ORDER_CANCELLED = 'ORDER_CANCELLED';
    const ERROR_ORDER_NOT_FOUND = 'ORDER_NOT_FOUND';
    const ERROR_ORDER_ALREADY_BEEN_TAKEN = 'ORDER_ALREADY_BEEN_TAKEN';
    const ERROR_INCORRECT_PARAMETERS = 'Incorrect Parameters';

    /**
     * @var Request
     */
    private $request;

    /**
     * UpdateOrderHandler constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        if ($this->request->get('status') !== static::STATUS_TAKEN) {
            throw new \InvalidArgumentException(static::ERROR_INCORRECT_PARAMETERS);
        }
    }

    /**
     * @param $id
     * @return array
     * @throws OrderAlreadyBeenTakenException
     * @throws OrderCancelledException
     * @throws OrderCompletedException
     * @throws OrderNotFoundException
     */
    public function execute($id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->status === Order::STATUS_CANCELLED) {
                throw new OrderCancelledException(static::ERROR_ORDER_CANCELLED);
            }

            if ($order->status === Order::STATUS_COMPLETED) {
                throw new OrderCompletedException(static::ERROR_ORDER_COMPLETED);
            }

            if ($order->status === Order::STATUS_ASSIGNED) {
                throw new OrderAlreadyBeenTakenException(static::ERROR_ORDER_ALREADY_BEEN_TAKEN);
            }

            $order->status = ORDER::STATUS_ASSIGNED;
            $order->save();

            return ['status' => static::STATUS_MESSAGE_SUCCESS];

        } catch (ModelNotFoundException $e) {
            throw new OrderNotFoundException(static::ERROR_ORDER_NOT_FOUND);
        }
    }
}