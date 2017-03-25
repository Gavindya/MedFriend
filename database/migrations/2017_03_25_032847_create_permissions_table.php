<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //status 0 when requested
        //status 1 when accepted
        //status 2 when timed out
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('doctor_ID');
            $table->integer('patient_ID');
            $table->string('request');
            $table->timestamps();
            $table->tinyInteger('status')->default('0');
            $table->foreign('doctor_ID')->references('doctor_id')->on('doctors');
            $table->foreign('patient_ID')->references('patient_id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
