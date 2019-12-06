<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitorRequest extends FormRequest
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
            'first_name' =>'required|max:20',
            'last_name'  =>'required|max:20',
            'email'      =>'required|max:50',
            'company'    =>'required|max:50',
            'accept'     =>'required',
            'confirm'    =>'required',
        ];
    }
}
