<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('working_hiurs', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->date('datum');
			$table->integer('oznaka_id');
			$table->integer('sati');
			$table->string('napomena');
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
		Schema::drop('working_hiurs');
    }
}
