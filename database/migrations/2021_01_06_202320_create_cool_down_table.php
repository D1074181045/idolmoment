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
            $table->timestamp('signature')->nullable()->comment('簽名冷卻時間');
            $table->timestamp('activity')->nullable()->comment('個人活動冷卻時間');
            $table->timestamp('cooperation')->nullable()->comment('合作活動冷卻時間');
            $table->timestamp('operating')->nullable()->comment('操作冷卻時間');
            $table->timestamp('chat')->nullable()->comment('聊天冷卻時間');

            $table->foreign('name')->on('game_info')->references('name')->onDelete('cascade')->onUpdate('cascade');

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
