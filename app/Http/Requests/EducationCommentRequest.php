<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationCommentRequest extends FormRequest
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
			'comment'		=> 'required|max:1000',
            'article_id'	=> 'required'
        ];
    }
	
	public function messages()
	{
		return [
			'comment.required'		=> 'Unos teksta je obavezan!',
			'comment.max'			=> 'Dozvoljen je unos maximalno 1000 znaka!',
			'article_id.required'	=> 'Unos je obavezan!'
		];
	}
}
