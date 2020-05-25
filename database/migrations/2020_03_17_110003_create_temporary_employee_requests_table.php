<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporaryEmployeeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_employee_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zahtjev',10)->nullable($value = true);
			$table->integer('employee_id');
			$table->date('GOpocetak')->nullable($value = true);
            $table->date('GOzavršetak')->nullable($value = true);
            $table->time('vrijeme_od')->nullable($value = true);
			$table->time('vrijeme_do')->nullable($value = true);
			$table->string('napomena',255)->nullable($value = true);
			$table->string('odobreno',10)->nullable($value = true);
			$table->string('odobreno2',10)->nullable($value = true);
			$table->string('razlog',255)->nullable($value = true);
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
        Schema::dropIfExists('temporary_employee_requests');
    }
}
