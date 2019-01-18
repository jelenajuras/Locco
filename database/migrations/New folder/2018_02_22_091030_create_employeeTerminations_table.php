<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTerminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('employee_terminations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id');
			$table->integer('otkaz_id');
			$table->string('otkazni_rok');
			$table->date('datum_odjave');
			$table->string('napomena')->nullable($value = true);
            $table->timestamps();
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('otkaz_id')->references('id')->on('terminations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employee_terminations');
    }
}
