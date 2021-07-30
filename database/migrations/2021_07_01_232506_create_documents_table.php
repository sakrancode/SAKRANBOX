<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('co_id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->string('storage')->nullable()->comment("nama Folder/File didalam storage");
            $table->enum('type', ['FOLDER', 'FILE'])->default('FOLDER');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('level')->default(1);
            // $table->enum('status', ['P', 'A'])->default('P')->comment('P:Pending, A:Approve');

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
        Schema::dropIfExists('documents');
    }
}
