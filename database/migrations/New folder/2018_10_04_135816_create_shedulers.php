<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShedulers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shedulers', function (Blueprint $table) {
			$table->increments('id');
			$table->date('datum')->nullable($value = true);
			$table->integer('employee_id')->nullable($value = true);
			$table->integer('project_id')->nullable($value = true);
			$table->integer('car_id')->nullable($value = true);
			$table->string('napomena')->nullable($value = true);
			$table->string('mjesto_rada')->nullable($value = true);
			$table->timestamps();
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('project_id')->references('id')->on('projects');
		});
			
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shedulers');
    }
}
