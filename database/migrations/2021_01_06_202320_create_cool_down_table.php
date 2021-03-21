<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoolDownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cool_down', function (Blueprint $table) {
            $table->string('name', 15)->comment('使用者名稱')->primary();
            $table->dateTime('signature')->nullable()->comment('簽名冷卻時間');
            $table->dateTime('activity')->nullable()->comment('活動冷卻時間');
            $table->dateTime('operating')->nullable()->comment('操作冷卻時間');
            $table->dateTime('chat')->nullable()->comment('聊天冷卻時間');

            $table->foreign('name')->on('game_info')->references('name')->onDelete('cascade');

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
        Schema::dropIfExists('cool_down');
    }
}
