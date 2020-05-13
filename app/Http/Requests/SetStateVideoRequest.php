<?php

namespace App\Http\Requests;

use App\Video;
use Illuminate\Foundation\Http\FormRequest;

class SetStateVideoRequest extends FormRequest
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
        $states = Video::STATE_ACCEPTED . ',' . Video::STATE_BLOCKED . ',' . Video::STATE_PENDING;
        return [
            'state' => 'required|in:' . $states
        ];
    }
}
