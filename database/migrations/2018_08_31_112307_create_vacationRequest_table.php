<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id');
			$table->date('GOpocetak')->nullable($value = true);
			$table->date('GOzavršetak')->nullable($value = true);
			$table->string('napomena')->nullable($value = true);
			$table->string('odobreno')->nullable($value = true);
			$table->integer('odobrio_id');
			$table->date('datum_odobrenja')->nullable($value = true);
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
        Schema::dropIfExists('vacation_requests');
    }
}
