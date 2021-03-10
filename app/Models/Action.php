<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed users_id
 * @property mixed type
 * @property mixed amount
 * @property false|mixed|string date_time
 */
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(BotUsers::class, 'users_id');
    }

    private function accruals($userId, $amount): bool
    {
        try {
            DB::beginTransaction();

            $action = new self;
            $action->users_id = $userId;
            $action->type = '+';
            $action->amount = $amount;
            $action->date_time = date("Y-m-d H:i:s");
            $action->save();

            $user = BotUsers::find($userId);
            $user->satoshi += $amount;
            $user->total += $amount;
            $user->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getBitcoin($userId, $amount): bool
    {
        return $this->accruals($userId, $amount);
    }

    public function accrualsForReferrers($referrer): ?array
    {
        if(!$this->accruals($referrer, (defined('SATOSHI_INVITE') ? SATOSHI_INVITE : 0)))
            return null;
        $referrer2 = RefSystem::where('referral', $referrer)->first();
        if($referrer2) {
            if(!$this->accruals($referrer, (defined('SATOSHI_INVITE_2') ? SATOSHI_INVITE_2 : 0)))
                return null;
        }
        return [
            $referrer,
            $referrer2
        ];
    }

    public function withdraw($userId, $comment): bool
    {
        try {
            DB::beginTransaction();

            $user = BotUsers::find($userId);
            $withdrawal = new Withdrawal();
            $withdrawal->users_id = $userId;
            $withdrawal->satoshi = $user->satoshi;
            $withdrawal->comment = $comment;
            $withdrawal->date_time = date("Y-m-d H:i:s");
            $withdrawal->save();
            $user->satoshi = 0;
            $user->save();
            $action = new self;
            $action->users_id = $userId;
            $action->type = '-';
            $action->amount = $user->satoshi;
            $action->date_time = date("Y-m-d H:i:s");
            $action->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
