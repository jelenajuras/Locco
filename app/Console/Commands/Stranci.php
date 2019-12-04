<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\EmployeeTermination;
Use Mail;
use DateTime;

class Stranci extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Stranci';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Istek dozvole za boravak';

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
		$datum->modify('75 days');
		$dan = date_format($datum,'d');
		$mjesec= date_format($datum,'m');
		$godina= date_format($datum,'Y');
		
		$djelatnici = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name')->whereYear('registrations.datum_dozvola', '=', $godina)->whereMonth('registrations.datum_dozvola', '=', $mjesec)->whereDay('registrations.datum_dozvola', '=', $dan)->get();
		
		foreach($djelatnici as $djelatnik) {
			$otkaz = EmployeeTermination::where('employee_terminations.employee_id','=',$djelatnik->employee_id)->first();
			if(!$otkaz){
			$ime = $djelatnik->first_name;
			$prezime = $djelatnik->last_name;
			// Send the email to user
				Mail::queue('email.Stranci', ['djelatnik' => $djelatnik,'ime' => $ime, 'prezime' => $prezime], function ($mail) use ($djelatnik ) {
					$mail->to('pravni@duplico.hr')
						->to('uprava@duplico.hr')
						->from('info@duplico.hr', 'Duplico')
						->subject('Istek dozvole za boravak ' . ' - ' . $djelatnik->first_name . ' '. $djelatnik->last_name);
				});
			}
		}

		$this->info('Obavijest je poslana!');
    }
}
