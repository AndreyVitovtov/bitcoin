<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $table = 'actions';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'users_id',
        'type',
        'amount',
        'date_time'
    ];

    public function user()
    {
        return $this->belongsTo(BotUsers::class, 'users_id');
    }
}
