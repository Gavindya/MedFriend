<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicineInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_ID');
            $table->string('medicine');
            $table->string('info');
            $table->timestamps();
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
        Schema::dropIfExists('medicine_infos');
    }
}
