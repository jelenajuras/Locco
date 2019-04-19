<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluatingQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('evaluating_questions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('group_id')->nullable($value = true);
			$table->string('naziv')->nullable($value = true);
			$table->string('opis')->nullable($value = true);
			$table->text('opis2')->nullable($value = true);
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
        Schema::drop('evaluating_questions');
    }
}
