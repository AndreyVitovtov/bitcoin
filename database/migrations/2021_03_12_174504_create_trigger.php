<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER `referral_system_AFTER_INSERT` AFTER INSERT ON `referral_system` FOR EACH ROW
            BEGIN
                UPDATE `users` SET `count_ref` = (
                    SELECT COUNT(*)
                    FROM `referral_system`
                    WHERE `referrer` = NEW.referrer
                )
                WHERE `id` = NEW.referrer;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER `referral_system_AFTER_INSERT`");
    }
}
