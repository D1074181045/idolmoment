<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersUpMagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters_up_mag', function (Blueprint $table) {
            $table->string('character_name', 50)->comment('英文名稱')->primary();

            $table->float('vitality')->unsigned()->comment('生命值');
            $table->float('energy')->unsigned()->comment('精力');
            $table->float('resistance')->unsigned()->comment('抗壓性');
            $table->float('charm')->unsigned()->comment('魅力');

            $table->foreign('character_name')->on('game_characters')->references('en_name')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('characters_up_mag');
    }
}
