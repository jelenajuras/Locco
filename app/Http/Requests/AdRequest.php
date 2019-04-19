<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
            'category_id' => 'required',
            'subject' => 'required',
            'description' => 'required',
        ];
    }
	
	public function messages()
	{
		return [
			'employee_id.required' => 'Unos djelatnika je obavezan.',
			'category_id.required' => 'Unos kategorije je obavezan',
			'subject.required'	   => 'Unos naslova je obavezan',
			'description.required' => 'Unos teksta oglasa je obavezan',
		];
	}
}
