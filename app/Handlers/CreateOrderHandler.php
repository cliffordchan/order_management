<?php

namespace App\Handlers;

use App\Libraries\DistanceCalculator as DistanceCalculator;
use App\Libraries\Converter\CoordinateConverter;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Class CreateOrderHandler
 * @package App\Handlers
 */
class CreateOrderHandler
{
    const ERROR_INCORRECT_PARAMETERS = 'Incorrect Parameters';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var DistanceCalculator
     */
    private $calculator;

    /**
     * CreateOrderHandler constructor.
     * @param Request $request
     * @param DistanceCalculator $calculator
     */
    public function __construct(Request $request, DistanceCalculator $calculator)
    {
        $this->request = $request;
        $this->calculator = $calculator;

        if (!($this->request->has('origin') && $this->request->has('destination'))) {
            throw new \InvalidArgumentException(static::ERROR_INCORRECT_PARAMETERS);
        }
    }

    /**
     * Converts input coordinates and create an order
     *
     * @return array
     * @throws \App\Exceptions\DistanceUnavailableException
     */
    public function execute()
    {
        $originCoordinate = CoordinateConverter::get($this->request->get('origin'));
        $destinationCoordinate = CoordinateConverter::get($this->request->get('destination'));

        // API call to Google for distance with the given origin and destination
        $distance = $this->calculator
            ->setGoogleAPIkey(env('GOOGLE_DISTANCE_MATRIX_API_KEY'))
            ->setSource($originCoordinate)
            ->setDestination($destinationCoordinate)
            ->setUseSslVerifier(false)
            ->getDistanceInMeters();

        $order = new Order();
        $order->origin_latitude = $originCoordinate->getLatitude();
        $order->origin_longitude = $originCoordinate->getLongitude();
        $order->destination_latitude = $destinationCoordinate->getLatitude();
        $order->destination_longitude = $destinationCoordinate->getLongitude();
        $order->distance = $distance;
        $order->save();
        $order->refresh();

        return [
            'id' => $order->id,
            'distance' => $order->distance,
            'status' => $order->status,
        ];
    }
}