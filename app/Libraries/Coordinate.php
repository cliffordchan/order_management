<?php

namespace App\Libraries;

use App\Exceptions\InvalidCoordinateException;
use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;

/**
 * Class Coordinate
 *
 * This is a really slim down version of coordinate value object class
 *
 * @see https://en.wikipedia.org/wiki/ISO_6709
 * @package App\Libraries
 */
class Coordinate
{
    const EXCEPTION_INVALID_LATITUDE = 'Invalid latitude: %s';
    const EXCEPTION_INVALID_LONGITUDE = 'Invalid longitude %s';

    /**
     * @var string
     */
    protected $latitude;

    /**
     * @var string
     */
    protected $longitude;

    /**
     * Coordinate constructor.
     *
     * @param int|string $latitude
     * @param int|string $longitude
     * @throws InvalidCoordinateException
     */
    public function __construct($latitude = '', $longitude = '')
    {
        $latitudeRule = new LatitudeRule();
        $longitudeRule = new LongitudeRule();

        if (!$latitudeRule->passes('test', $latitude)) {
            throw new InvalidCoordinateException(
                sprintf(static::EXCEPTION_INVALID_LATITUDE, $latitude)
            );
        }

        if (!$longitudeRule->passes('test', $longitude)) {
            throw new InvalidCoordinateException(
                sprintf(static::EXCEPTION_INVALID_LONGITUDE, $longitude)
            );
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Return the coordinate as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLatitude() . ',' . $this->getLongitude();
    }
}
