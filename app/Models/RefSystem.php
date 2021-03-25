<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $array)
 * @method static where(string $string, $id)
 * @method static orderBy(string $string, string $string1)
 */
class RefSystem extends Model
{
    protected $table = 'referral_system';
    public $timestamps = false;
    public $fillable = [
        'referrer',
        'referral',
        'datetime'
    ];
}
