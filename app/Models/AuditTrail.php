<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditTrail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['activity', 'user_id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function Log(string $activity) {
        $_user = auth()->user();

        AuditTrail::query()->create([
            'user_id' => $_user->id,
            'activity' => $activity
        ]);
    }
}
