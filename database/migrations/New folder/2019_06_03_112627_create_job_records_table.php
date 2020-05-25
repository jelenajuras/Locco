<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_records', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id');
			$table->date('date');
			$table->string('task');
			$table->string('odjel');
			$table->time('time');
			$table->integer('task_manager');
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
        Schema::dropIfExists('job_records');
    }
}
