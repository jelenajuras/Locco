<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\EmployeeTraining;
use App\Models\EmployeeTermination;
Use Mail;
use DateTime;

class Osposobljavanje extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Osposobljavanje';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Osposobljavanje';

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
		$datum->modify('+2 month');
		$dan = date_format($datum,'d');
		$mjesec= date_format($datum,'m');
		$godina= date_format($datum,'Y');
		
		$employeeTrainings = EmployeeTraining::whereYear('expiry_date', '=', $godina)->whereMonth('expiry_date', '=', $mjesec)->whereDay('expiry_date', '=', $dan)->get();
		
		foreach($employeeTrainings as $employeeTraining) {
			$otkaz = EmployeeTermination::where('employee_id','=',$employeeTraining->employee_id)->first();
			if(!$otkaz){
			$ime = $employeeTraining->employee['first_name'];
			$prezime = $employeeTraining->employee['last_name'];
			// Send the email to user
				Mail::queue('email.Osposobljavanje', ['employeeTraining' => $employeeTraining,'ime' => $ime, 'prezime' => $prezime], function ($mail) use ($employeeTraining) {
					$mail->to('pravni@duplico.hr')
						->from('info@duplico.hr', 'Duplico')
						->subject('Istek ' . ' osposobljavanja ' . ' - ' . $employeeTraining->employee['first_name'] . ' '. $employeeTraining->employee['last_name']);
				});
			}
		}

		$this->info('Obavijest je poslana!');
    }
}
