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
     * @param array $coordinate
     * @return Coordinate
     */
    public static function get($coordinate)
    {
        if (!is_array($coordinate)) {
            throw new \UnexpectedValueException('Incorrect format of coordinates provided');
        }

        if (2 !== count($coordinate)) {
            throw new \InvalidArgumentException('Incorrect number of coordinates provided');
        }

        return new Coordinate($coordinate[0], $coordinate[1]);
    }
}
