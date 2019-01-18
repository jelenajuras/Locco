<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluatingQuestionRequest extends FormRequest
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
            'group_id'  => 'required',
			'naziv'  => 'required|max:255',
			'opis'  => 'max:300'
        ];
    }
	
	public function messages()
	{
		return [
			'group_id.required'	=> 'Unos grupe je obavezan!',
			'naziv.required'	=> 'Unos naziva je obavezan!',
			'naziv.max'			=> 'Dozvoljen je unos maximalno 255 znaka!',
			'opis.max'			=> 'Dozvoljen je unos maximalno 300 znaka!'
		];
	}
}
