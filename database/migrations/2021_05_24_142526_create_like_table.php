<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like', function (Blueprint $table) {
            $table->string('from_name', 15);
            $table->string('to_name', 15);

            $table->enum('type', ['dislike', 'like']);

            $table->primary(['from_name', 'to_name']);
            $table->foreign('from_name')->on('users')->references('name')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('to_name')->on('users')->references('name')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('like');
    }
}
