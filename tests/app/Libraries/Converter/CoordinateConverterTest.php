<?php

namespace Tests\Libraries\Converter;

use App\Libraries\Converter\CoordinateConverter;
use Tests\TestCase;

/**
 * Class CoordinateConverterTest
 * @package Tests\Libraries
 */
class CoordinateConverterTest extends TestCase
{
    /**
     * @param int $latitude
     * @param int $longitude
     * @param array $coordinate
     * @dataProvider coordinateProvider
     */
    public function testCoordinate($latitude, $longitude, $coordinate)
    {
        $coordinate = CoordinateConverter::get($coordinate);
        $this->assertInstanceOf('\App\Libraries\Coordinate', $coordinate);
        $this->assertEquals($latitude, $coordinate->getLatitude());
        $this->assertEquals($longitude, $coordinate->getLongitude());
    }

    /**
     * @return array
     */
    public function coordinateProvider()
    {
        return [
            [40.20361, -74.00587, [40.20361, -74.00587]],
            [40.20888, -74.00445, [40.20888, -74.00445]],
        ];
    }

    /**
     * @expectedException \App\Exceptions\InvalidCoordinateException
     */
    public function testInvalidCoordinate()
    {
        CoordinateConverter::get([-274.00587, -174.00587]);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testNonArrayCoordinate()
    {
        CoordinateConverter::get('-274.00587, -174.00587');
    }
}
