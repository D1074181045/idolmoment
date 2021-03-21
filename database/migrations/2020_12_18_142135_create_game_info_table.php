<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGameInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_info', function (Blueprint $table) {
            $table->string('name', 15)->comment('使用者名稱')->primary();
            $table->string('nickname', 12)->comment('遊戲暱稱')->unique();
            $table->string('use_character', 50)->comment('使用角色');

            $table->integer('popularity')->unsigned()->comment('人氣')->default(1);
            $table->integer('reputation')->comment('名聲')->default(1);
            $table->integer('max_vitality')->unsigned()->comment('最大生命值');
            $table->integer('current_vitality')->comment('目前生命值');
            $table->integer('energy')->unsigned()->comment('精力');
            $table->integer('resistance')->unsigned()->comment('抗壓性');
            $table->integer('charm')->unsigned()->comment('魅力');

            $table->integer('rebirth_counter')->unsigned()->comment('轉生次數')->default(0);
            $table->boolean('graduate')->comment('畢業')->default(false);

            $table->string('signature', 30)->nullable()->comment('簽名');
            $table->string('teetee', 12)->nullable()->comment('貼貼');

            $table->foreign(['name', 'use_character'])->on('own_characters')->references(['name', 'character_name'])->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        DB::statement('CREATE INDEX "game_info_popularity_index" ON "game_info" ("popularity" DESC)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_info');
    }
}
