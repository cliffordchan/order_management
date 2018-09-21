<?php

namespace App\Libraries;

use App\Exceptions\DistanceUnavailableException;
use Redbox\Distance\CalculateDistance as Calculator;

/**
 * Class DistanceCalculator
 * @package App\Libraries
 */
class DistanceCalculator extends Calculator
{
    /**
     * Return the distance calculated to meters
     *
     * Origin package mark the calculateDistance function as private that
     * returns distance in meters and it's extremely useful not to round it
     * up to kilometers.
     *
     * @return float|int
     * @throws DistanceUnavailableException
     */
    public function getDistanceInMeters()
    {
        $reflectedMethod = new \ReflectionMethod($this, 'calculateDistance');
        $reflectedMethod->setAccessible(true);
        $route = $reflectedMethod->invoke($this);

        if (is_null($route) === false && isset($route->distance->value)) {
            return $route->distance->value;
        }

        throw new DistanceUnavailableException('Google does not return a valid distance');
    }
}
