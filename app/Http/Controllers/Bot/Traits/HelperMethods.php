<?php


namespace App\Http\Controllers\Bot\Traits;


use App\Models\Action;
use App\Models\BotUsers;
use App\Models\buttons\InlineButtons;
use App\Models\buttons\Menu;
use App\Models\Channel;
use App\Models\RefSystem;

trait HelperMethods
{
    public function checkSubscription($viber = false) {
        $channel = Channel::where('bots_id', BOT['id'])->first();
        if(MESSENGER == 'Telegram') {
            if($channel && $channel->channels_id && !$this->getChatMember($this->getChat(), $channel->channels_id)) {
                $this->send('{check_subscription}',
                    InlineButtons::checkSubscription($channel->channels_name), true, [], [
                        'link' => ''
                    ]
                );
                die;
            }
        } elseif($viber) {
            if($channel && $channel->channels_name) {
                $this->send('{check_subscription}', Menu::subscribed('1'), false, [], [
                    'link' => $channel->channels_name
                ]);
                die;
            }
        }
    }

    public function subscribed($count = 1)
    {
        if($count == '2') {
            $this->main();
        } else {
            $channel = Channel::where('bots_id', BOT['id'])->first();
            $this->send('{check_subscription}', Menu::subscribed('2'), false, [], [
                'link' => $channel->channels_name
            ]);
        }

    }

    private function statisticsUser(): array
    {
        $user = $this->getUser();
        return [
            'count_invite' => $user->count_ref,
            'satoshi' => $user->satoshi,
            'satoshi_invite' => (defined('SATOSHI_INVITE') ? SATOSHI_INVITE : 0),
            'satoshi_invite_2' => (defined('SATOSHI_INVITE_2') ? SATOSHI_INVITE_2 : 0)
        ];
    }

    private function walletUser(): array {
        $user = $this->getUser();
        return [
            'balance' => $user->satoshi,
            'balance_btc' => bcdiv($user->satoshi, '100000000', 8),
            'total' => $user->total,
            'total_btc' => bcdiv($user->total, '100000000', 8),
        ];
    }

    private function checkIfTheDayHasPassed() {
        $user = $this->getUser();
        return 86400 - (time() - strtotime($user->getbitcoin));
    }

    private function timeToHours(): ?array {
        $time = $this->checkIfTheDayHasPassed();
        $sign = gmp_sign($time);
        if($sign == 1) {
            list($h, $m) = explode(":", date("H:i", $time));
            return [
                'h' => str_replace('0', '', $h),
                'm' => $m
            ];
        } else {
            return null;
        }
    }

    public function performAnActionRef($referrer)
    {
        $action = new Action();
        if($referrers = $action->accrualsForReferrers($referrer)) {
            $referrer = BotUsers::find($referrers[0]->id);
            $this->sendTo($referrer->chat, '{satoshi_ref}', Menu::main(), false, [], [
                'satoshi' => (defined('SATOSHI_INVITE') ? SATOSHI_INVITE : 0)
            ]);
            if($referrers[1]) {
                $referrer = BotUsers::find($referrers[1]);
                $this->sendTo($referrer->chat, '{satoshi_ref}', Menu::main(), false, [], [
                    'satoshi' => (defined('SATOSHI_INVITE_2') ? SATOSHI_INVITE_2 : 0)
                ]);
            }
            $this->stock($referrer);
        } else {
            echo "Error. Referrers are not credited with satoshi";
        }
    }

    private function getLink(): string {
        if(MESSENGER == 'Telegram') {
            return 'https://telegram.me/'.BOT['name'].'?start='.$this->getChat();
        } else {
            return 'viber://pa?chatURI='.BOT['name'].'&context='.$this->getChat();
        }
    }

    private function stock($user) {
        if($user->count_ref >= STOCK_COUNT_INVITE) {
            $dateTime = date(
                'Y-m-d H:i:s',
                strtotime('-'.STOCK_TIME.' HOURS', strtotime(date('Y-m-d H:i:s')))
            );
            $count = RefSystem::where('referrer', $user->id)
                ->where('datetime', '>=', $dateTime)
                ->count();
            if($count == STOCK_COUNT_INVITE) {
                $action = new Action();
                if($action->accruals($user->id, STOCK_PRIZE)) {
                    $this->sendTo($user->chat, '{stock_prize}', Menu::main(), false, [], [
                        'count_invite' => STOCK_COUNT_INVITE,
                        'satoshi' => STOCK_PRIZE,
                        'hours' => STOCK_TIME
                    ]);
                } else {
                    echo "Error. Referrers are not credited with satoshi";
                }
            }
        }
    }
}
