<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\EmployeeTermination;
use App\Models\EvaluatingEmployee;
use App\Models\VacationRequest;
use App\Models\Employee;
use App\Models\Questionnaire;
Use Mail;
use DateTime;
use DateInterval;
use DatePeriod;

class Anketa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:Anketa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anketa';

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
		$danas2 = date_format($datum,'d.m.Y');
		$datum2 = $datum->modify('-6 month');
		$danas = date_format($datum2,'Y-m-d');
		
		$registrations = Registration::get();
		$questionnaires = Questionnaire::get();
		
		foreach($questionnaires as $questionnaire){
			if($questionnaire->status == 'aktivan'){
				foreach($registrations as $registration) {
					if(! DB::table('employee_terminations')->where('employee_id',  $registration->employee_id)->first()){
						$evaluatingEmployees = EvaluatingEmployee::where('employee_id',$registration->employee_id)->where('questionnaire_id', $questionnaire->id)->where('status', 'OK')->get();
						if(count($evaluatingEmployees) < 15){
							if($danas > $registration->datum_prijave ){
								$email  = $registration->employee['email'];
								$brojAnketa = count($evaluatingEmployees);
								Mail::queue('email.Anketa', ['brojAnketa' => $brojAnketa, 'registration' => $registration ], function ($mail) use ($danas2, $registration, $email) {
									$mail->to($email)
										->from('info@duplico.hr', 'Duplico')
										->subject('Anketa na dan ' . $danas2 . ' - ' . $registration->employee['first_name'] . ' ' . $registration->employee['last_name']);
								});
							}
						}
					}
				}
			}
		}
		
		$this->info('GO messages sent successfully!');
	}
}