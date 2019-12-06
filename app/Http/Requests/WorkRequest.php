<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
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
            'odjel'  =>'required',
			'naziv'  =>'required',
			'user_id'  =>'required'
        ];
    }
	
	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'odjel.required' => 'Unos odjela je obavezan',
			'naziv.required'  => 'Unos naziva radnog mjesta je obavezan',
			'user_id.required'  => 'Unos nadređene osobe je obavezan'
		];
	}
}
