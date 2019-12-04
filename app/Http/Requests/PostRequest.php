<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
			'title'  => 'required|max:255',
			'content'=> 'required|max:65535',
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
            'title.required'    => 'Unos naslova poruke je obavenzan',
            'title.max'         => 'Dozvoljen je unos maximalno 255 znaka',
            'content.max'       => 'Dozvoljen je unos maximalno 65535 byte',
			'content.required'  => 'Unos poruke je obavenzan',
		];
	}
}
