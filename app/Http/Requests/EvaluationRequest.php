<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
            'employee_id'	=> 'required',
			'datum'			=> 'required',
			'rating'		=> 'required',
			'question_id' 	=> 'required',
			'group_id' 		=> 'required',
			'koef' 			=> 'required'
			
			
        ];
    }
	
	public function messages()
	{
		return [
			'employee_id.required'	=> 'Unos djelatnika je obavezan!',
			'datum.required'		=> 'Unos datuma je obavezan!',
			'rating.required'		=> 'Unos ocjene je obavezan!',
			'question_id.required'	=> 'Unos je obavezan!',
			'group_id.required'		=> 'Unos je obavezan!',
			'koef.required'			=> 'Unos je obavezan!'
		];
	}
}
