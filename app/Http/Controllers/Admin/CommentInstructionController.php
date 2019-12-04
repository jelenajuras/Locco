<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instruction;
use App\Models\CommentInstruction;
use App\Models\Employee;
use App\Http\Requests\CommentInstructionRequest;
use Sentinel;
use Mail;

class CommentInstructionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $instruction = Instruction::where('id', $request['instruction_id'])->first();

		$user = Sentinel::getUser();
		$employee = Employee::where('employees.last_name',$user->last_name)->where('employees.first_name',$user->first_name)->first();
		
		$data = array(
			'employee_id'  => $employee->id,                 
			'instruction_id'  =>  $request['instruction_id'],    //$input['post_id'],
			'content'  =>  $request['content']     // )$input['content']
		);
		
		$comment = new CommentInstruction();
		$comment->saveCommentInstruction($data);
		
		$email = 'uprava@duplico.hr';
    //    $email = 'jelena.juras@duplico.hr';
        $link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/instructions/'. $instruction->id ;
        
       
		Mail::queue(
			'email.komentar_radna_uputa',
			['employee' => $employee, 'link' => $link, 'instruction' => $instruction],
			function ($message) use ($email, $employee) {
				$message->to($email)
					->from($email, $employee->first_name . ' ' .  $employee->last_name)
					->subject('Komentar na radnu uputu');
			}
		);
		
		$message = session()->flash('success', 'You have successfully addad a new comment.');
		
		return redirect()->back()->withFlashMessage($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
