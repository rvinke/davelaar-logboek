<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'first_name'            => 'required|between:1,80',
            'last_name'             => 'required|between:3,80',
            'email'                 => 'required|between:5,64|email|unique:users',
            'password'              => 'min:6|confirmed',
            'password_confirmation' => 'required_with:password|min:6',
        ];
    }
}
