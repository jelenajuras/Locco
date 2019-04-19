<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenefitRequest extends FormRequest
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
            'name'			=> 'required|max:255',
            'description'	=> 'required|max:500',
			'comment'		=> 'max:1000'
        ];
    }
	
	public function messages()
	{
		return [
			'name.required'		=> 'Unos naziva je obavezan!',
			'name.max'			=> 'Dozvoljen je unos maximalno 255 znaka!',
			'description.required'	=> 'Unos opisa je obavezan!',
			'description.max'		=> 'Dozvoljen je unos maximalno 500 znaka!',
			'comment.max'			=> 'Dozvoljen je unos maximalno 1000 znaka!'
		];
	}
}
