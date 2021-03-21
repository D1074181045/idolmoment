<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_characters', function (Blueprint $table) {
            $table->string('en_name', 50)->comment('英文名稱')->primary();
            $table->string('tc_name', 50)->comment('中文名稱')->unique();
            $table->string('img_file_name', 50)->comment('圖檔名稱');

            $table->integer('vitality')->unsigned()->comment('生命值');
            $table->integer('energy')->unsigned()->comment('精力');
            $table->integer('resistance')->unsigned()->comment('抗壓性');
            $table->integer('charm')->unsigned()->comment('魅力');

            $table->string('introduction')->comment('介紹');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_characters');
    }
}
