<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEffectiveHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('effective_hours', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('employee_id')->nullable($value = true);
			$table->double('effective_cost', 8, 2);
			$table->double('brutto', 8, 2);
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
        Schema::drop('effective_hours');
    }
}
