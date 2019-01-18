<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
		$table->string('ime_oca')->after('last_name');
		$table->string('ime_majke')->after('ime_oca');
		$table->string('oi')->after('oib');
		$table->string('mjesto_rodjenja')->after('datum_rodjenja');
		$table->string('konf_velicina')->after('ZNR');
		$table->string('broj_cipela')->after('konf_velicina');
		$table->date('oi_istek')->nullable();
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
