<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('username', '255')->unique();
            $table->string('password', '64');
            $table->integer('permission')->unsigned()->default(1);
            $table->string('first_name', '255');
            $table->string('last_name', '255');
            $table->string('phone', '16');
            $table->string('line_token', '128')->nullable(true);
            $table->string('remember_token', '255')->default('');
            // $table->timestamps();
            $timestamps = false;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
