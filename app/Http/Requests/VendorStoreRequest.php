<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
            //
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:admin',
            'password' => 'required|string|min:6|max:10',
            'shop' => 'required|string',
            'phone' => 'required|integer',
            'avatar' =>''
        ];
    }
}
