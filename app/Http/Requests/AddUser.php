<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class AddUser extends FormRequest
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
            'mail' => Config::get("constante.validation.mail"),
            'username' => Config::get("constante.validation.username"),
            'password' => Config::get("constante.validation.password"),
            'password_confirm' => Config::get("constante.validation.password_confirm"),
         ];
    }
}
