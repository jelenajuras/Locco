<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluatingEmployeeRequest extends FormRequest
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
			'ev_employee_id'	=> 'required',
			'mjesec_godina'		=> 'required',
			'questionnaire_id'		=> 'required',
        ];
    }
	
	public function messages()
	{
		return [
			'employee_id.required'		=> 'Unos djelatnika je obavezan!',
			'ev_employee_id.required'	=> 'Unos djelatnika je obavezan!',
			'questionnaire_id.required'	=> 'Unos ankete je obavezan!',
			'mjesec_godina.required'	=> 'Unos je obavezan!'
		];
	}
}
