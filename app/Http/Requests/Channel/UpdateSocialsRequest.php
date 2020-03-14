<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialsRequest extends FormRequest
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
            'cloob' => 'nullable|url|min:9',
            'lenzor' => 'nullable|url|min:9',
            'instagram' => 'nullable|url|min:9',
            'whatsapp' => 'nullable|url|min:9',
            'facebook' => 'nullable|url|min:9',
            'twitter' => 'nullable|url|min:9',
            'telegram' => 'nullable|url|min:9'
        ];
    }
}
