<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\API\Telegram;
use App\Models\API\Viber;
use App\Models\Bot;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Bots extends Controller
{
    public function list()
    {
        return view('admin.bots.list', [
            'bots' => Bot::all(),
            'menuItem' => 'list_bots'
        ]);
    }

    public function add()
    {
        return view('admin.bots.add', [
            'languages' => Language::all(),
            'menuItem' => 'add_bot'
        ]);
    }

    public function addSave(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request = $request->post();
        if (empty($request['language']) || empty($request['token']) || empty($request['name'])) return redirect()->back();
        try {
            DB::beginTransaction();
            $bot = new Bot();
            $bot->languages_id = $request['language'];
            $bot->token = $request['token'];
            $bot->name = $request['name'];
            $bot->save();
            $url = url('bot/index/' . $bot->id);
            if ($request['messenger'] == 'telegram') {
                $messenger = new Telegram($bot->token);
            } else {
                $messenger = new Viber($bot->token);
            }
            $messenger->setWebhook($url);
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->to(route('list-bots'));
    }
}
