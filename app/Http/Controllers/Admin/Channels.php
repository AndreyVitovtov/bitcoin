<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Channel;
use Illuminate\Http\Request;

class Channels extends Controller
{
    public function index()
    {
        return view('admin.channels.index', [
            'bots' => Bot::all(),
            'menuItem' => 'channels'
        ]);
    }

    public function save(Request $request)
    {
        $request = $request->post();
        foreach($request['name'] as $botId => $name) {
            $channel = Channel::where('bots_id', $botId)->first();
            if(!$channel) {
                $channel = new Channel();
            }
            $channel->channels_id = $request['id'][$botId] ?? null;
            $channel->channels_name = $name;
            $channel->bots_id = $botId;
            $channel->save();
        }
        return redirect()->to(route('channels'));
    }
}
