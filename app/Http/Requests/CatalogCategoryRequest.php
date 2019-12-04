<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogCategoryRequest extends FormRequest
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
            'name' 	        => 'required|max:100',
            'description' 	=> 'max:255',
        ];
    }

    public function messages()
	{
		return [
			'name.required'     => 'Unos naziva je obavezan!',
			'name.max'          => 'Dozvoljen je unos max. :max znakova!',	
            'description.max'   => 'Dozvoljen je unos max. :max znakova!'
		];
	}
}
