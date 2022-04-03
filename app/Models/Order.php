<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Enum\RoleName;
use App\Events\OrderProcessingEvent;
use App\Events\OrderReadyShipmentEvent;
use App\Events\OrderReceivedEvent;
use App\Events\OrderShippedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kleemans\AttributeEvents;

/**
 * @method static find(int $id)
 * @method static orderOpen()
 * @method static create(array $validated)
 * @method static findOrFail(int $id)
 */
class Order extends Model
{

    use SoftDeletes, AttributeEvents;


    protected $dispatchesEvents = [
        'order_status:'.OrderStatus::ORDER_RECEIVED => OrderReceivedEvent::class,
        'order_status:'.OrderStatus::ORDER_PROCESSING => OrderProcessingEvent::class,
        'order_status:'.OrderStatus::ORDER_READY_TO_SHIP => OrderReadyShipmentEvent::class,
        'order_status:'.OrderStatus::ORDER_SHIPPED => OrderShippedEvent::class,
        'order_status:'.OrderStatus::ORDER_CANCELLED => OrderShippedEvent::class,
    ];

    protected $fillable = [
        'name',
        'phone',
        'address',
        'delivery_date',
        'product_id',
        'payment_option',
        'amount',
        'order_status',
        'comment'
    ];

    public function cancel($comment) {
        $this->update([
            'order_status' => OrderStatus::ORDER_CANCELLED,
            'comment' => $comment
        ]);
    }

    public function scopeOrderOpen($query) {
        if (auth()->user()->hasRole([RoleName::SHIPPING, RoleName::PICKING]))
            $query->where('order_status', '!=', OrderStatus::ORDER_CANCELLED);
    }

    public function scopeOrderPermission($query) {
        if (auth()->user()->hasRole(RoleName::SHIPPING))
            $query->where('order_status', '=', OrderStatus::ORDER_READY_TO_SHIP);
        elseif (auth()->user()->hasRole(RoleName::PICKING))
            $query->whereIn('order_status', [OrderStatus::ORDER_RECEIVED, OrderStatus::ORDER_PROCESSING]);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function shipment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderShipment::class, 'order_id');
    }

    public function addShipment($shipment)
    {
        $this->shipment()->updateOrCreate($shipment);
    }

    public function activity_logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderActivityLog::class, 'order_id');
    }
}
