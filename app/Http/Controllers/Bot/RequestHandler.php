<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Traits\HelperMethods;
use App\Http\Controllers\Bot\Traits\MethodsFromGroupAndChat;
use App\Http\Controllers\Bot\Traits\BasicMethods;
use App\Models\Action;
use App\Models\buttons\Menu;
use App\Models\buttons\RichMedia;

class RequestHandler extends BaseRequestHandler
{

    use BasicMethods;
    use MethodsFromGroupAndChat;
    use HelperMethods;

    public function getBitcoin()
    {
        $this->checkSubscription();
        $time = $this->timeToHours();
        if (!$time) {
            $action = new Action();
            $amount = (defined('SATOSHI_GET_BITCOIN') ? SATOSHI_GET_BITCOIN : 0);
            if ($action->getBitcoin($this->getUserId(), $amount)) {
                $this->send('{get_bitcoin}', Menu::main(), false, [], [
                    'satoshi' => $amount
                ]);
            } else {
                $this->send('{error}', Menu::main());
            }
        } else {
            if ($time['h'] == '0') {
                $message = '{you_cant_get_bitcoin}';
            } else {
                $message = '{you_cant_get_bitcoin_h}';
            }
            $this->send($message, Menu::main(), false, [], $time);
        }
    }

    public function myWallet()
    {
        $this->checkSubscription();
        $this->send('{my_wallet}', Menu::myWallet(), false, [], $this->walletUser());
    }

    public function instruction()
    {
        $this->checkSubscription();
        $this->send('{instruction}', Menu::main());
    }

    public function earnEvenMore()
    {
        $this->checkSubscription();
        $this->send('{earn_even_more}', Menu::main(), false, [], $this->statisticsUser());
        $this->send('{link_for_invitation}', Menu::main(), false, [], [
            'link' => $this->getLink()
        ]);
    }

    public function withdrawal()
    {
        $this->checkSubscription();
        $user = $this->getUser();
        $count = (defined('NUMBER_OF_REFERRALS_FOR_WITHDRAWAL') ? NUMBER_OF_REFERRALS_FOR_WITHDRAWAL : 0);
        $amount = (defined('MINIMUM_WITHDRAWAL_AMOUNT') ? MINIMUM_WITHDRAWAL_AMOUNT : 0);
        if ($user->count_ref >= $count) {
            if ($user->satoshi >= $amount) {
                $this->setInteraction('sendWallet');
                $this->send('{send_wallet}', Menu::back(), false, [
                    'input' => 'regular'
                ]);
            } else {
                $this->send('{minimum_withdrawal_amount}', Menu::myWallet(), false, [], [
                    'satoshi' => $amount
                ]);
            }
        } else {
            $this->send('{not_enough_invitees}', Menu::myWallet(), false, [], [
                'count' => $count,
                'count_invite' => $user->count_ref,
                'satoshi' => (defined('SATOSHI_INVITE') ? SATOSHI_INVITE : 0),
                'link' => $this->getLink()
            ]);
        }
    }

    public function sendWallet()
    {
        $this->checkSubscription();
        if ($this->getType() == 'text' || $this->getType() == 'message') {
            $action = new Action();
            if ($action->withdraw($this->getUserId(), $this->getMessage())) {
                $this->send('{withdrawal_request_sent}', Menu::main());
            } else {
                $this->send('{error}', Menu::main());
            }
            $this->delInteraction();
        } else {
            $this->send('{send_wallet}', Menu::back(), false, [
                'input' => 'regular'
            ]);
        }
    }
}
