<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EducationCommentRequest;
use App\Models\EducationComment;
use App\Models\Employee;
use Sentinel;

class EducationCommentController extends Controller
{
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
    public function store(EducationCommentRequest $request)
    {
		$user = Sentinel::getUser();
		$employee_id = Employee::where('first_name',$user->first_name)->where('last_name',$user->last_name)->first()->id;
		
		$input = $request;

		$data = array(
			'article_id'  	=> $input['article_id'],
			'employee_id'   => $employee_id,
			'comment'  		=> $input['comment']
		);
			
		$educationComment = new EducationComment();
		$educationComment->saveEducationComment($data);
		
		$message = session()->flash('success', 'Komentar je snimljen');

		return redirect()->route('admin.educations.show', $input['education_id'] )->withFlashMessage($message);
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
