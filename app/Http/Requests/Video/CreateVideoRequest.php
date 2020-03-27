<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryIdRule;
use App\Rules\PlaylistIdRule;
use App\Rules\UploadedVideoIdRule;
use App\Rules\UploadedBannerIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
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
            'video_id' => ['required', new UploadedVideoIdRule()],
            'title' => 'required|string|max:255',
            'category' => ['required', new CategoryIdRule(CategoryIdRule::PUBLIC_CATEGORIES)],
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlist' => ['nullable', new PlaylistIdRule()],
            'channel_category' => ['nullable', new CategoryIdRule(CategoryIdRule::PRIVATE_CATEGORIES)],
            'banner' => ['nullable', new UploadedBannerIdRule()],
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
        ];
    }
}
