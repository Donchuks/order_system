<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(int $id)
 * @method static orderOpen()
 * @method static create(array $validated)
 */
class Product extends Model
{

    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'price',
        'currency'
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}
