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
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Female');
            // $table->string('phone', 16);
            // $table->enum('level', ['A', 'O', 'K'])->default('O')->comment('A:Administrator, O:Officer, K:Kapus');
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('Y:Aktif, N:Blok');
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
