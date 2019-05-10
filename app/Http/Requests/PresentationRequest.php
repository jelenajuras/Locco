<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresentationRequest extends FormRequest
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

    public function rules()
    {
        return [
            'subject'		=> 'required',
            'article'		=> 'required',
            'employee_id'	=> 'required',
            'theme_id'		=> 'required'
        ];
    }
	
	public function messages()
	{
		return [
			'subject.required'		=> 'Unos naslova je obavezan!',
			'article.required'		=> 'Unos teksta je obavezan!',
			'employee_id.required'	=> 'Unos je obavezan!',
			'theme_id.required'		=> 'Unos teme je obavezan!'
		];
	}
}
