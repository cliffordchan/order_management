<?php

namespace App\Libraries\Converter;

use App\Libraries\Coordinate;

/**
 * Class CoordinateConverter
 *
 * A simple class to convert input coordinate to an object
 *
 * @package App\Libraries\Converter
 */
class CoordinateConverter
{
    /**
     * @param string $coordinate
     * @return Coordinate
     */
    public static function get($coordinate)
    {
        list($latitude, $longitude) = array_map(
            function ($input) {
                return trim($input);
            },
            array_pad(
                explode(',', $coordinate, 2),
                2,
                null
            )
        );

        return new Coordinate($latitude, $longitude);
    }
}
