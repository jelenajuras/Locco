<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'employee_id'		=> 'required',
             'questionnaire_id'	=> 'required',
             'question_id'		=> 'required',
             'mjesec_godina'	=> 'required',
             'result'			=> 'required',
             'target'			=> 'required|max:255',
             'comment'			=> 'required|max:255'
        ];
    }
	
	public function messages()
	{
		return [
			'employee_id.required'	=> 'Unos je obavezan!',
			'questionnaire_id.required'	=> 'Unos je obavezan!',
			'question_id.required'	=> 'Unos je obavezan!',
			'mjesec_godina.required'	=> 'Unos je obavezan!',
			'result.required'	=> 'Unos je obavezan!',
			'target.required'	=> 'Unos je obavezan!',
			'comment.required'	=> 'Unos je obavezan!',
			'target.max'		=> 'Dozvoljen je unos maximalno 255 znaka!',
			'comment.max'		=> 'Dozvoljen je unos maximalno 255 znaka!'
		];
	}
}