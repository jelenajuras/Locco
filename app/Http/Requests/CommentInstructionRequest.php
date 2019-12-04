<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentInstructionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Sentinel::check())
		{
			return true;
		}
			return false;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'=>'required|max:67535'
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
            'content.required'  => 'A comment content is required',
            'content.max'		=> 'Dozvoljen je unos maximalno :max znaka!'
		];
	}
}
