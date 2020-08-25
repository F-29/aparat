<?php

namespace App\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;

class LikeVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // TODO: only logged in users should be able to do this
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'like' => 'required|boolean'
        ];
    }
}
