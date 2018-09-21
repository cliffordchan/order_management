<?php

namespace Tests\Libraries;

use App\Libraries\Coordinate;
use Tests\TestCase;

/**
 * Class CoordinateTest
 * @package Tests\Libraries
 */
class CoordinateTest extends TestCase
{
    /**
     * @param int $latitude
     * @param int $longitude
     * @param string $expectedToString
     * @dataProvider coordinateProvider
     */
    public function testCoordinate($latitude, $longitude, $expectedToString)
    {
        $coordinate = new Coordinate($latitude, $longitude);
        $this->assertInstanceOf('\App\Libraries\Coordinate', $coordinate);
        $this->assertSame($latitude, $coordinate->getLatitude());
        $this->assertSame($longitude, $coordinate->getLongitude());
        $this->assertSame($expectedToString, $coordinate->__toString());
    }

    /**
     * @return array
     */
    public function coordinateProvider()
    {
        return [
            [40.20361, -74.00587, '40.20361,-74.00587'],
            [40.20888, -74.00445, '40.20888,-74.00445'],
        ];
    }

    /**
     * @expectedException \App\Exceptions\InvalidCoordinateException
     */
    public function testInvalidCoordinate()
    {
        new Coordinate(-274.00587, -174.00587);
    }
}
