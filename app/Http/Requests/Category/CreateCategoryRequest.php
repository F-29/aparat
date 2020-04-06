<?php

namespace App\Http\Requests\Category;

use App\Rules\UploadedCategoryBannerIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:100|unique:categories,title',
            'icon' => 'string|nullable', // TODO: decide which package should we use
            'banner' => ['nullable', 'string', new UploadedCategoryBannerIdRule()]
        ];
    }
}