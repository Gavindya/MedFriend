<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->string('title',5);
            $table->date('birthday');
            $table->string('gender',10);
            $table->string('nationality',80)->nullable();
            $table->string('blood_group',5)->nullable();
            $table->decimal('height',8)->nullable();
            $table->decimal('weight',8)->nullable();
            $table->string('hair_color',45)->nullable();
            $table->string('eye_color',45)->nullable();
            $table->string('mobile',15)->nullable();
            $table->foreign('patient_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
