<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
			$table->string('oib');
			$table->date('datum_rodjenja')->nullable();
			$table->string('mobitel');
			$table->string('email');
			$table->string('prebivaliste_adresa');
			$table->string('prebivaliste_grad');
			$table->string('boraviste_adresa')->nullable();
			$table->string('boraviste_grad')->nullable();
			$table->string('zvanje')->nullable();
			$table->string('sprema')->nullable();
			$table->string('bracno_stanje');	
			$table->integer('broj_djece')->nullable();
			$table->string('radnoMjesto_id')->nullable();
			$table->timestamp('lijecn_pregled')->nullable();
			$table->timestamp('ZNR')->nullable();
			$table->text('napomena')->nullable();
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
        Schema::drop('employees');
    }
}
