<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, $id)
 */
class Channel extends Model
{
    protected $table = 'channels';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'channel_id',
        'channel_name',
        'bots_id'
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bots_id');
    }
}
