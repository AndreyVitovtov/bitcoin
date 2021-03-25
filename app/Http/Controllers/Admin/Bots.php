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


/**
 * @method static all(string $string, string $string1, string $string2)
 */
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
            $bot->messenger = $request['messenger'];
            $bot->save();
            $url = url('bot/index/' . $bot->id);
            if ($request['messenger'] == 'telegram') {
                $messenger = new Telegram($bot->token);
            } else {
                $messenger = new Viber($bot->token);
            }
            DB::commit();
            $messenger->setWebhook($url);
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->to(route('list-bots'));
    }

    public function delete(Request $request)
    {
        $request = $request->post();
        $bot = Bot::find($request['id']);
        if($bot->messenger == 'telegram') {
            $messenger = new Telegram($bot->token);
        } else {
            $messenger = new Viber($bot->telegram);
        }
        $messenger->deleteWebhook();
        Bot::where('id', $request['id'])->delete();
        return redirect()->to(route('list-bots'));
    }
}
