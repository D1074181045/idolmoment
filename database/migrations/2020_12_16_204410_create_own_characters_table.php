<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_characters', function (Blueprint $table) {
//            $table->id();
            $table->string('name', 15)->comment('使用者名稱');
            $table->string('character_name', 50)->comment('角色名稱')->default('Minato Aqua');

//            $table->unique(['name', 'character_name']);
            $table->primary(['name', 'character_name']);
            $table->foreign('name')->on('users')->references('name')->onDelete('cascade');
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
        Schema::dropIfExists('own_characters');
    }
}
