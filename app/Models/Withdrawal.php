<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user() {
        return $this->belongsTo(BotUsers::class, 'users_id');
    }
}
