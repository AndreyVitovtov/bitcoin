<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static find($id)
 * @method static where(string $string, mixed $id)
 * @property mixed languages_id
 * @property mixed token
 * @property mixed name
 * @property mixed id
 * @property mixed messenger
 */
class Bot extends Model
{
    protected $table = 'bots';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'token',
        'name',
        'languages_id',
        'messenger'
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }

    public function channel(): HasOne
    {
        return $this->hasOne(Channel::class, 'bots_id');
    }
}
