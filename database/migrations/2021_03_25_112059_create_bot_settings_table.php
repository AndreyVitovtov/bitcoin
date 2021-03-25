<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bots_id')->unsigned();
            $table->float('satoshi_invite')->default('2');
            $table->float('satoshi_invite_2')->default('1');
            $table->float('satoshi_get_bitcoin')->default('1');
            $table->integer('number_of_referrals_for_withdrawal')->default('1');
            $table->float('minimum_withdrawal_amount')->default('1');
            $table->integer('stock_count_invite')->nullable();
            $table->integer('stock_time')->nullable();
            $table->float('stock_prize')->nullable();

            $table->index('bots_id');

            $table->foreign('bots_id')
                ->references('id')->on('bots')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_settings');
    }
}
