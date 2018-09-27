<?php

namespace Tests\Handlers;

use App\Handlers\CreateOrderHandler;
use App\Libraries\DistanceCalculator;
use App\Models\Order;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as m;
use Tests\TestCase;

/**
 * Class CreateOrderHandlerTest
 * @package Tests\Libraries
 */
class CreateOrderHandlerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @dataProvider coordinatesProvider
     * @param $parameters
     * @param $expected
     * @throws \App\Exceptions\DistanceUnavailableException
     */
    public function testHandlerReturnsArray($parameters, $expected)
    {
        $calculator = m::mock(DistanceCalculator::class);
        $calculator->shouldReceive('setGoogleAPIkey')->andReturn($calculator);
        $calculator->shouldReceive('setSource')->andReturn($calculator);
        $calculator->shouldReceive('setDestination')->andReturn($calculator);
        $calculator->shouldReceive('setUseSslVerifier')->with(false)->andReturn($calculator);
        $calculator->shouldReceive('getDistanceInMeters')->andReturn(555);

        $request = new Request();
        $request->setMethod('POST');
        $request->merge($parameters);

        $createOrderHandler = new CreateOrderHandler($request, $calculator);
        $response = $createOrderHandler->execute();

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('distance', $response);
        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @return array
     */
    public function coordinatesProvider()
    {
        return [
            [
                [
                    'origin' => [40.20361,-74.00587],
                    'destination' => [40.20888,-74.00445],
                ],
                [
                    'distance' => 555,
                    'status' => Order::STATUS_UNASSIGN,
                ]
            ]
        ];
    }

    /**
     * @expectedException \App\Exceptions\InvalidCoordinateException
     */
    public function testInvalidCoordinateThrowsException()
    {
        $parameters = [
            'origin' => [-274.00587,-174.00587],
            'destination' => [40.20888,-74.00445],
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

        $createOrderHandler = new CreateOrderHandler($request, $calculator);
        $createOrderHandler->execute();
    }
}
