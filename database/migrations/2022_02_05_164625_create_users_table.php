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
            $table->uuid('uuid')->unique('unique_uuid_users');
            $table->string('fullname', 80);
            $table->string('email')->unique('unique_email_users');
            $table->string('phone', 20)->nullable()->unique('unique_phone_users');
            $table->date('birth_date')->nullable();
            $table->string('nickname', 60)->unique('unique_nickname_users');
            $table->string('password');
            $table->string('real_password', 50);

            $table->rememberToken();

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
