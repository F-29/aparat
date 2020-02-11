<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterVerifyUserRequest extends FormRequest
{
    use GetRegisterTypeAndValueTrait;

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
            'code' => 'required|string',
            'mobile' => ['required_without:email', 'string', new MobileRule()],
            'email' => 'required_without:mobile|email'
        ];
    }

    public function code()
    {
        return $this->code;
    }
}
