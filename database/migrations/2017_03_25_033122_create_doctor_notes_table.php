<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_ID');
            $table->integer('doctor_ID');
            $table->string('note');
            $table->timestamps();
            $table->foreign('file_ID')->references('file_id')->on('files');
            $table->foreign('doctor_ID')->references('doctor_id')->on('doctors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_notes');
    }
}
