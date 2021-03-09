<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @property mixed languages_id
 * @property mixed token
 * @property mixed name
 * @property mixed id
 */
class Bot extends Model
{
    protected $table = 'bots';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'token',
        'name',
        'languages_id'
    ];

    public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }
}
