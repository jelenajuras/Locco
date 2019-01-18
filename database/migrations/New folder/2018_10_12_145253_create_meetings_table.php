<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
			$table->increments('id');
			$table->date('datum')->nullable($value = true);
			$table->integer('employee_id')->nullable($value = true);
			$table->integer('meeting_room_id')->nullable($value = true);
			$table->integer('project_id')->nullable($value = true);
			$table->string('description')->nullable($value = true);
			$table->time('vrijeme_od')->nullable($value = true);
			$table->time('vrijeme_do')->nullable($value = true);
			$table->timestamps();
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('meeting_room_id')->references('id')->on('meeting_rooms');
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
        Schema::drop('meetings');
    }
}
