<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sentinel;
use App\Models\Notice;
use App\Models\Registration;
use App\Http\Requests\NoticeRequest;
use App\Http\Controllers\Controller;
use Mail;

class NoticeController extends Controller
{
     /**
   * Set middleware to quard controller.
   *
   * @return void
   */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::orderBy('created_at','DESC')->get();
		
		return view('admin.notices.index',['notices'=>$notices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Sentinel::getUser()->id;

		return view('admin.notices.create',['user'=>$user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticeRequest $request)
    {
        $input = $request->except(['_token']);
		$registrations = Registration::join('employees','registrations.employee_id', '=', 'employees.id')->select('registrations.*','employees.first_name','employees.last_name','employees.email')->orderBy('employees.last_name','ASC')->get();
		
		$data = array(
			'employee_id'  => $input['employee_id'],
			'subject'   => $input['subject'],
			'notice'  => $input['notice']
		);
		
		$notice = new Notice();
		$notice->saveNotice($data);

		$poruka = $notice->subject;
		
		foreach($registrations as $registration){
			if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() ){
				$to = $registration->email;
				Mail::queue(
					'email.notice',
					['poruka' => $poruka],
					function ($message) use ($to , $poruka) {
						$message->to($to)
							->from('info@duplico.hr', 'Duplico')
							->subject('Obavijest uprave');
					}
				);
			}
		}
			
		$message = session()->flash('success', 'Obavijest je poslana');
		
		//return redirect()->back()->withFlashMessage($messange);
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
		
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notice = Notice::find($id);

		return view('admin.notices.show', ['notice' => $notice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Sentinel::getUser()->id;
		$notice = Notice::find($id);
		
		return view('admin.notices.edit', ['notice' => $notice], ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);
		$input = $request->except(['_token']);
		//dd($input);
		$data = array(
			'employee_id'  => $input['employee_id'],
			'subject'   => $input['subject'],
			'notice'  => $input['notice']
		);
		
		$notice->updateNotice($data);
		
		// $to = 'svi@duplico.hr';
		// $to = 'jelena.juras@duplico.hr';
		
		$to = 'zeljko.rendulic@duplico.hr';
		$subject = $input['subject'];
		$user = Sentinel::getUser()->email;
		$poruka_id = $notice->id;
		
		Mail::queue(
			'email.notice',
			['subject' => $subject, 'poruka_id' => $poruka_id ],
			function ($message) use ($to , $subject) {
				$message->to($to)
					->from('info@duplico.hr', 'Duplico')
					->subject('Obavijest');
			}
		);
		
		$message = session()->flash('success', 'Obavijest je ispravljena');
		
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notice = Notice::find($id);
		$notice->delete();
		
		$message = session()->flash('success', 'Obavijest je obrisana.');
		
		return redirect()->route('admin.notices.index')->withFlashMessage($message);
    }
}
