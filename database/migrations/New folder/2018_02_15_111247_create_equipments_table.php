<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
			$table->string('naziv');
			$table->string('napomena')->nullable($value = true);
			$table->integer('količina_monter')->nullable($value = true);
			$table->integer('količina_inženjer')->nullable($value = true);
			$table->integer('User_id')->nullable($value = true);
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
        Schema::drop('equipments');
    }
}
