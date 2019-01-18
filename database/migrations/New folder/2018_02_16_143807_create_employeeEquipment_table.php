<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('employee_equipments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id');
			$table->integer('equipment_id');
			$table->integer('kolicina');
			$table->date('datum_zaduzenja')->nullable();
			$table->date('datum_povrata')->nullable();
			$table->string('napomena')->nullable($value = true);
            $table->timestamps();
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('equipment_id')->references('id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employee_equipments');
    }
}
