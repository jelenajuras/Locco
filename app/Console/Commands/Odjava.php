<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeTermination;
use App\Models\Employee;
use App\Models\Work;
Use Mail;
use DateTime;

class Odjava extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Odjava';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Odjava djelatnika';

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
      $djelatnici = EmployeeTermination::join('employees','employee_terminations.employee_id', '=', 'employees.id')->join('registrations','employee_terminations.employee_id', '=','registrations.employee_id')->join('works', 'registrations.radnoMjesto_id', '=', 'works.id')->select('employee_terminations.*','employees.first_name','employees.last_name','works.naziv')->whereYear('employee_terminations.datum_odjave', '=', date('Y'))->whereMonth('employee_terminations.datum_odjave', '=', date('m'))->whereDay('employee_terminations.datum_odjave', '=', date('d'))->get();

		foreach($djelatnici as $djelatnik) {
			$ime = $djelatnik->first_name;
			$prezime = $djelatnik->last_name;
			$radno_mj= $djelatnik->naziv;
			
			// Send the email to user
				Mail::queue('email.Odjava', ['djelatnik' => $djelatnik, 'ime' => $ime, 'prezime' => $prezime, 'radno_mj' => $radno_mj], function ($mail) use ($djelatnik) {
					$mail->to('uprava@duplico.hr')
						->cc('jelena.juras@duplico.hr')
						->cc('pravni@duplico.hr')
						->cc('petrapaola.bockor@duplico.hr')
						->cc('tomislav.novosel@duplico.hr')
						->cc('marina.sindik@duplico.hr')
						->cc('marica.posaric@duplico.hr')
						->from('info@duplico.hr', 'Duplico')
						->subject('Odjava radnika ' . ' - ' . $djelatnik->first_name . ' ' . $djelatnik->last_name);
				});

		}

		$this->info('Obavijest je poslana!');
    }
}
