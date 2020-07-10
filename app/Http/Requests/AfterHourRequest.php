<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AfterHourRequest extends FormRequest
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
			'employee_id' => 'required',
			'datum' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'napomena' => 'required',
        ];
    }
	
	public function messages()
	{
		return [
			'employee_id.required' => 'Unos djelatnika je obavezan.',
			'datum.required' => 'Unos datuma je obavezan',
			'start_time.required' => 'Unos vremena je obavezan',
			'end_time.required' => 'Unos vremena je obavezan',
			'napomena.required' => 'Unos napomene je obavezan',
		];
	}
}
