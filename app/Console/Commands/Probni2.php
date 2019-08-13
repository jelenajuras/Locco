<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\EmployeeTermination;
Use Mail;
use DateTime;

class Probni2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Probni2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probni rok';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $datum = new DateTime('now');
		$datum->modify('-6 month');
		$datum->modify('+1 month');
		$dan = date_format($datum,'d');
		$mjesec= date_format($datum,'m');
		$godina= date_format($datum,'Y');
		
		$djelatnici = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->whereYear('registrations.datum_prijave', '=', $godina)->whereMonth('registrations.datum_prijave', '=', $mjesec)->whereDay('registrations.datum_prijave', '=', $dan)->get();
		
		foreach($djelatnici as $djelatnik) {
			$otkaz = EmployeeTermination::where('employee_terminations.employee_id','=',$djelatnik->employee_id)->first();
			if(!$otkaz){
			// Send the email to user
				Mail::queue('email.Probni', ['djelatnik' => $djelatnik, 'ime' => $ime, 'prezime' => $prezime], function ($mail) use ($djelatnik ) {
					$mail->to('uprava@duplico.hr')
						->cc('andrea.glivarec@duplico.hr')
						->cc('matija.barberic@duplico.hr')
						->cc('jelena.juras@duplico.hr')
						->from('info@duplico.hr', 'Duplico')
						->subject('Probni ' . ' ' . ' rok ' . $djelatnik->first_name . ' ' . $djelatnik->last_name);
				});
			}
		}

		$this->info('Obavijest je poslana!');
    }
}