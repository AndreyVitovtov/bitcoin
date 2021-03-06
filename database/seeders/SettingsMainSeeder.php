<?php

namespace Database\Seeders;

use App\Models\SettingsMain;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsMainSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $data = [
            ["id" => "1","prefix" => "count_mailing","name" => "По сколько сообщений рассылать за один раз:","name_us" => "How many messages to send at one time:","value" => "200","type" => "number"],
            ["id" => "2","prefix" => "sleep_mailing","name" => "Задержка между рассылками, секунд:","name_us" => "Delay between mailings, seconds:","value" => "2","type" => "number"]
        ];

        $dataFile = \App\Models\Seeder::getMain();

        $seeder = (count($dataFile) > count($data)) ? $dataFile : $data;

        DB::table('settings_main')->insert($seeder);

        $settingsMain = SettingsMain::all();
        foreach($settingsMain as $sm) {
            $res[$sm['prefix']] = $sm['value'];
        }

        file_put_contents(public_path("json/main.json"), json_encode($res));
    }
}
