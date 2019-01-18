<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
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
            'naziv'	=> 'required|max:255',
			'status'	=> 'required|max:255'
        ];
    }
	
	public function messages()
	{
		return [
			'naziv.required'	=> 'Unos naziva je obavezan!',
			'naziv.max'			=> 'Dozvoljen je unos maximalno 255 znaka!',
			'status.required'	=> 'Unos statusa je obavezan!'
		];
	}
}
