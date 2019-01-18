<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobInterviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_interviews', function (Blueprint $table) {
			$table->increments('id');
			$table->date('datum')->nullable($value = true);
			$table->integer('employee_id')->nullable($value = true);
			$table->string('oib')->nullable($value = true);
			$table->string('email')->nullable($value = true);
			$table->string('telefon')->nullable($value = true);
			$table->string('sprema')->nullable($value = true);
			$table->string('zvanje')->nullable($value = true);
			$table->integer('radnoMjesto_id')->nullable($value = true);
			$table->integer('godine_iskustva')->nullable($value = true);
			$table->string('napomena')->nullable($value = true);
			$table->double('placa')->nullable($value = true);
			$table->string('jezik')->nullable($value = true);
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
        Schema::drop('job_interviews');
    }
}
