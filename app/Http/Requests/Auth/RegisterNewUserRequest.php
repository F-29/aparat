<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterNewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: only the unregistered users be able to request this
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
            'mobile' => ['required_without:email', 'string', new MobileRule()],
            'email' => 'required_without:mobile|email',
        ];
    }
}
