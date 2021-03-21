<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('name', 15)->comment('使用者名稱')->primary();
            $table->string('password')->comment('密碼');
//            $table->string('api_token', 80)->nullable()->unique()->default(null);
            $table->rememberToken();

            $table->dateTime('logged_at')->nullable()->comment('登入時間');

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
        Schema::dropIfExists('users');
    }
}
