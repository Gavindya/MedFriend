<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_ID');
            $table->integer('family_member_ID');
            $table->tinyInteger('status')->default('0');
            $table->timestamps();
            $table->foreign('family_member_ID')->references('patient_id')->on('patients');
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
        Schema::dropIfExists('family_requests');
    }
}
