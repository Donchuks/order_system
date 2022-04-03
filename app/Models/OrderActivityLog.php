<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(int $id)
 * @method static orderOpen()
 * @method static create(array $validated)
 */
class OrderActivityLog extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'current_state',
        'activity',
        'user_id',
    ];


    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
