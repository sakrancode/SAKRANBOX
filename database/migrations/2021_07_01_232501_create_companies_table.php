<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            // $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('npwp', 16)->nullable();
            $table->string('address');
            $table->string('city', 50);
            $table->string('phone', 15)->nullable();
            $table->text('description')->nullable();
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('Y:Aktif, N:Blok');
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
        Schema::dropIfExists('companies');
    }
}
