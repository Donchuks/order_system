<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method where(string $string, string $operator, $string2)
 * @method static find(mixed $id)
 * @method static isActive()
 * @method static create(mixed $array)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'gender',
        'email',
        'password',
        'logged_int_at',
        'logged_out_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function audit(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuditTrail::class, 'user_id');
    }

    public function fl_names(): string
    {
        $name = $this->first_name .' '. $this->last_name;
        return trim(str_replace('  ', ' ', $name));
    }

    public function getRole(): string
    {
        if (sizeof($this->getRoleNames()) > 0)
            return strtoupper(str_replace('_', ' ', $this->getRoleNames()->first()));
        return 'N/A';
    }

    public function scopeIsActive($query) {
        $query->where('status', 'active');
    }
}
