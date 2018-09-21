<?php

namespace Tests\App\Http\Controllers;

use App\Exceptions\Handler;
use App\Handlers\UpdateOrderHandler;
use App\Http\Controllers\OrderController;
use App\Libraries\DistanceCalculator;
use App\Models\Order;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as m;
use Tests\TestCase;

/**
 * Class OrderControllerTest
 */
class OrderControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function listOrdersEndpointShouldRespondWithStatusCode200()
    {
        $this->get('/orders')->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function listOrdersEndpointShouldReturnOrderCollection()
    {
        factory(Order::class, 2)->create();
        $this->get("orders");
        $this->seeStatusCode(Response::HTTP_OK);

        $rawContent = $this->response->getContent();
        $content = json_decode($rawContent, true);
        $this->assertCount(2, $content);
        $this->seeJsonStructure(['*' => ['id', 'distance', 'status']]);
    }

    /** @test */
    public function createOrderShouldReturnStatusCode200AndJsonResponse()
    {
        $parameters = [
            'origin' => '40.20361,-74.00587',
            'destination' => '40.20888,-74.00445',
        ];

        $calculator = m::mock(DistanceCalculator::class);
        $calculator->shouldReceive('setGoogleAPIkey')->andReturn($calculator);
        $calculator->shouldReceive('setSource')->andReturn($calculator);
        $calculator->shouldReceive('setDestination')->andReturn($calculator);
        $calculator->shouldReceive('setUseSslVerifier')->with(false)->andReturn($calculator);
        $calculator->shouldReceive('getDistanceInMeters')->andReturn(555);

        $request = new Request();
        $request->setMethod('POST');
        $request->merge($parameters);

        $controller = new OrderController($calculator);
        $response = $controller->createOrder($request);

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function createOrderShouldReturnStatusCode400WithInvalidCoordinate()
    {
        $this->expectException('\App\Exceptions\InvalidCoordinateException');

        $parameters = [
            'origin' => '40.20361,-184.00587',
            'destination' => '40.20888,-174.00445',
        ];

        $calculator = m::mock(DistanceCalculator::class);
        $calculator->shouldReceive('setGoogleAPIkey')->andReturn($calculator);
        $calculator->shouldReceive('setSource')->andReturn($calculator);
        $calculator->shouldReceive('setDestination')->andReturn($calculator);
        $calculator->shouldReceive('setUseSslVerifier')->with(false)->andReturn($calculator);
        $calculator->shouldReceive('getDistanceInMeters')->andReturn(555);

        $request = new Request();
        $request->setMethod('POST');
        $request->merge($parameters);

        $controller = new OrderController($calculator);
        $response = $controller->createOrder($request);
    }

    /**
     * @test
     */
    public function createOrderShouldFailedWithNoParameters()
    {
        $this->post('order', [])
            ->seeStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->seeJsonEquals([
                Handler::ERROR_DESCRIPTION => 'Incorrect Parameters'
            ]);
    }

    /** @test */
    public function getShouldNotMatchAnInvalidRoute()
    {
        $this
            ->get('/orders/invalid')
            ->seeStatusCode(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function updateOrderShouldFailWithNoParameter()
    {
        factory(Order::class)->create();
        $this
            ->put('/order/1000')
            ->seeStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->seeJsonEquals([
                Handler::ERROR_DESCRIPTION => 'Incorrect Parameters'
            ]);
    }

    /** @test */
    public function updateOrderShouldFailWithIncorrectParameter()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'notaken',
        ];

        $this
            ->put('/order/1', $parameters)
            ->seeStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->seeJsonEquals([
                Handler::ERROR_DESCRIPTION => 'Incorrect Parameters'
            ]);
    }

    /** @test */
    public function updateOrderShouldFailWithInvalidOrderId()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'taken',
        ];
        $this
            ->put("order/100", $parameters)
            ->seeStatusCode(Response::HTTP_NOT_FOUND)
            ->seeJsonEquals([
                Handler::ERROR_DESCRIPTION => UpdateOrderHandler::ERROR_ORDER_NOT_FOUND
            ]);
    }

    /** @test */
    public function updateOrderShouldRespondWithStatusCode409FailedMessage()
    {
        $order = factory(Order::class)->create();
        $order->status = ORDER::STATUS_ASSIGNED;
        $order->save();

        $parameters = [
            'status' => 'taken',
        ];
        $this
            ->put("order/1", $parameters)
            ->seeStatusCode(Response::HTTP_CONFLICT)
            ->seeJsonEquals([
                Handler::ERROR_DESCRIPTION => UpdateOrderHandler::ERROR_ORDER_ALREADY_BEEN_TAKEN
            ]);
    }

    /** @test */
    public function updateOrderShouldRespondWithStatusCode200SuccessfulMessage()
    {
        factory(Order::class)->create();
        $parameters = [
            'status' => 'taken',
        ];
        $this
            ->put("order/1", $parameters)
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonEquals([
                'status' => UpdateOrderHandler::STATUS_MESSAGE_SUCCESS
            ]);
    }
}
