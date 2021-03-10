<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed users_id
 * @property mixed satoshi
 * @property mixed comment
 * @property false|mixed|string date_time
 * @method static find(mixed $id)
 */
class Withdrawal extends Model
{
    protected $table = 'withdrawals';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'users_id',
        'satoshi',
        'comment',
        'status',
        'date_time'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(BotUsers::class, 'users_id');
    }
}
