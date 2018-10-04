<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfterHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('after_hours', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id');
			$table->date('datum')->nullable();
			$table->time('vrijeme_od')->after('GOzavršetak');
			$table->time('vrijeme_do')->after('vrijeme_od');
			$table->string('napomena')->nullable($value = true);
			$table->string('odobreno')->nullable($value = true);
			$table->integer('odobrio_id');
			$table->date('datum_odobrenja')->nullable();
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
        Schema::dropIfExists('after_hours');
    }
}
