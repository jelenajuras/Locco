<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\EmployeeTermination;
Use Mail;
use DateTime;

class Rodjendan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Rodjendan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rođendan djelatnika';


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
		$dan = date_format($datum,'d');
		$mjesec = date_format($datum,'m');
  		
		$djelatnici = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*', 'employees.first_name','employees.last_name', 'employees.datum_rodjenja','employees.email')->whereMonth('employees.datum_rodjenja', '=', $mjesec)->whereDay('employees.datum_rodjenja', '=', $dan)->get();
		
		foreach($djelatnici as $djelatnik) {
			$otkaz = EmployeeTermination::where('employee_terminations.employee_id','=',$djelatnik->employee_id)->first();
			if(!$otkaz){
				$ime = $djelatnik->first_name;
				$prezime = $djelatnik->last_name;
				$datum_rodjenja = $djelatnik->datum_rodjenja;
			// Send the email to user
				Mail::queue('email.Rodjendan', ['datum_rodjenja' => $datum_rodjenja,'djelatnik' => $djelatnik,'ime' => $ime, 'prezime' => $prezime], function ($mail) use ($djelatnik) {
					$mail->to('uprava@duplico.hr')
						->cc('jelena.juras@duplico.hr')
						->from('info@duplico.hr', 'Duplico')
						->subject('Rođendan ' . ' djelatnika ' . ' - ' . $djelatnik->first_name . ' '. $djelatnik->last_name);
				});
				Mail::queue('email.Cestitka', ['datum_rodjenja' => $datum_rodjenja,'djelatnik' => $djelatnik,'ime' => $ime, 'prezime' => $prezime], function ($mail) use ($djelatnik) {
					$mail->to($djelatnik->email)
						->from('info@duplico.hr', 'Duplico')
						->subject('Čestitka!');
				});
			}
		}
		
		$this->info('Birthday messages sent successfully!');
	}
}