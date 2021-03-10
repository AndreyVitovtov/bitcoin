<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bots')->insert([
            [
                "id" => "1",
                "token" => "4d06fc823427d210-7df7ddbab916444e-dccb1b20d0522675",
                "name" => "VitovtovBitcoinBot",
                "messenger" => "viber",
                "languages_id" => "1"
            ], [
                "id" => "2",
                "token" => "1683393637:AAFSeEXh1aYPlc9REK6fdPT6mFS8efzKU68",
                "name" => "VitovtovBitcoinBot",
                "messenger" => "telegram",
                "languages_id" => "1"
            ]
        ]);
    }
}
