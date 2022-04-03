<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(int $id)
 * @method static orderOpen()
 * @method static create(array $validated)
 */
class OrderShipment extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'shipping_company',
        'tracking_number',
        'shipping_attachment',
        'shipping_date',
        'order_id',
    ];


    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
