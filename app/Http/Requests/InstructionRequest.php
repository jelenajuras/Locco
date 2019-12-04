<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructionRequest extends FormRequest
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
            'title'		    => 'required|max:100',
            'department_id'	=> 'required',
            'description'	=> 'required|max:67535',
        ];
    }

    public function messages()
	{
		return [
			'title.required'	    => 'Unos naslova je obavezan!',
			'department_id.required'=> 'Unos odjela je obavezan!',
			'description.required'  => 'Unos teksta je obavezan!',
			'title.max'		        => 'Dozvoljen je unos maximalno :max znaka!',
			'description.max'		=> 'Dozvoljen je unos maximalno :max znaka!'
		];
	}
}
