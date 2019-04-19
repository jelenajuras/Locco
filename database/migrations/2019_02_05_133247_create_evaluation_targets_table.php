<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_targets', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('employee_id');
			$table->integer('questionnaire_id');
			$table->integer('question_id')->nullable($value = true);
			$table->integer('group_id')->nullable($value = true);
			$table->string('mjesec_godina');
			$table->string('result')->nullable($value = true);
			$table->string('target')->nullable($value = true);
			$table->string('comment')->nullable($value = true);
			$table->string('comment_uprava')->nullable($value = true);
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
        Schema::drop('evaluation_targets');
    }
}
