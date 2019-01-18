<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluatingEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluating_employees', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('employee_id');
			$table->integer('ev_employee_id');
			$table->integer('questionnaire_id');
			$table->string('mjesec_godina');
			$table->string('status');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('evaluating_employees');
    }
}
