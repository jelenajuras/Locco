<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Vacation_RequestRequest extends FormRequest
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
			'start_date' => 'required',
			'end_date' => 'date|after_or_equal:start_date',
			'napomena' => 'required|max:255',
			'employee_id' => 'required',
			'zahtjev' => 'required'
        ];
    }
	
	public function messages()
	{
		return [
			'start_date.required' 		 => 'Unos datuma je obavezan',
			'end_date.after_or_equal' => 'Datuma završetka ne može biti prije datuma početka.',
			'napomena.required' 		 => 'Unos napomene je obavezan',
			'napomena.max'				 => 'Dozvoljen je unos maximalno 255 znakova!',
			'employee_id.required' 		 => 'Unos imena je obavezan',
			'zahtjev.required'			 => 'Unos vrste zahteva je obavezan'
		];
	}
}
