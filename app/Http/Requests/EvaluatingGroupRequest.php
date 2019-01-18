<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluatingGroupRequest extends FormRequest
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
            'questionnaire_id' => 'required',
			'naziv' 		 => 'required|max:255',
			'koeficijent' 	 => 'required|numeric|max:8'
        ];
    }
	
	public function messages()
	{
		return [
			'questionnaire_id.required'	=> 'Unos ankete je obavezan!',
			'naziv.required'	=> 'Unos naziva je obavezan!',
			'naziv.size'		=> 'Dozvoljen je unos maximalno 255 znakova!',
			'koeficijent.required'	=> 'Unos koeficjenta je obavezan!',
			'koeficijent.numeric'	=> 'Dozvoljen je samo unos brojeva!',
			'koeficijent.size'		=> 'Dozvoljen je unos maximalno 8 znakova!'
		];
	}
}
