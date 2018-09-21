<?php

namespace Tests\Handlers;

use App\Handlers\UpdateOrderHandler;
use App\Models\Order;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class UpdateOrderHandlerTest
 * @package Tests\Handlers
 */
class UpdateOrderHandlerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @throws \App\Exceptions\OrderAlreadyBeenTakenException
     * @throws \App\Exceptions\OrderCancelledException
     * @throws \App\Exceptions\OrderCompletedException
     * @throws \App\Exceptions\OrderNotFoundException
     */
    public function testHandlerReturnsArray()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'taken',
        ];

        $request = new Request();
        $request->setMethod('PUT');
        $request->merge($parameters);

        $handler = new UpdateOrderHandler($request);
        $response = $handler->execute(1);

        $this->assertSame(['status' => UpdateOrderHandler::STATUS_MESSAGE_SUCCESS], $response);
    }

    /**
     * @expectedException  \App\Exceptions\OrderNotFoundException
     */
    public function testHandlerWithIncorrectIdAndThrowsException()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'taken',
        ];

        $request = new Request();
        $request->setMethod('PUT');
        $request->merge($parameters);

        $handler = new UpdateOrderHandler($request);
        $handler->execute(2);
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testHandlerWithIncorrectParametersAndThrowsException()
    {
        factory(Order::class)->create();
        $request = new Request();
        $request->setMethod('PUT');
        $request->merge([]);

        $handler = new UpdateOrderHandler($request);
        $handler->execute(2);
    }

    /**
     * @expectedException  \App\Exceptions\OrderAlreadyBeenTakenException
     */
    public function testHandlerWithOrderHasAlreadyBeenTakenAndThrowsException()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'taken',
        ];

        $request = new Request();
        $request->setMethod('PUT');
        $request->merge($parameters);

        $handler = new UpdateOrderHandler($request);
        $handler->execute(1);

        // Try to take ID 1 again
        $handler = new UpdateOrderHandler($request);
        $handler->execute(1);
    }

}
