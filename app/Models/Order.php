<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 */
class Order extends Model
{
    const STATUS_UNASSIGN = 'UNASSIGN';
    const STATUS_ASSIGNED = 'ASSIGNED';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var array
     */
    public $fillable = [
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'distance',
        'status',
    ];
}
