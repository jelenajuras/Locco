<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('odjel_id');
            $table->string('naziv');
			$table->string('job_description');
			$table->string('pravilnik')->nullable($value = true);
			$table->string('tocke')->nullable($value = true);
			$table->integer('user_id')->nullable($value = true);
			$table->integer('prvi_userId')->nullable($value = true);
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
        Schema::drop('works');
    }
}
