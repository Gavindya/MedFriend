<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('file_id');
            $table->integer('patient_ID');
            $table->integer('specialization_ID');
            $table->string('file_name');
            $table->string('description')->nullable();
            $table->string('extension');
            $table->timestamps();
            $table->foreign('patient_ID')->references('patient_id')->on('patients');
            $table->foreign('specialization_ID')->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
