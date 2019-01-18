<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluatingRatingRequest extends FormRequest
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
            'naziv' 		 => 'required|max:255',
			'rating' 	 	 => 'required|numeric'
        ];
    }
	
	public function messages()
	{
		return [
			'naziv.required'	=> 'Unos naziva je obavezan!',
			'naziv.max'			=> 'Dozvoljen je unos maximalno 255 znakova!',
			'rating.required'	=> 'Unos koeficjenta je obavezan!',
			'rating.numeric'	=> 'Dozvoljen je samo unos brojeva!'
		];
	}
}
