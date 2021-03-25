<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $bot)
 * @method static where(string $string, mixed $bot)
 */
class BotSettings extends Model
{
    protected $table = 'bot_settings';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'bots_id',
        'satoshi_invite',
        'satoshi_invite_2',
        'satoshi_get_bitcoin',
        'number_of_referrals_for_withdrawal',
        'minimum_withdrawal_amount',
        'stock_count_invite',
        'stock_time',
        'stock_prize'
    ];
}
