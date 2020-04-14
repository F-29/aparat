<?php

namespace App\Rules\Upload;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UploadedBannerIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Storage::disk('videos')->exists(env('BANNER_DIR') . DIRECTORY_SEPARATOR . $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid video banner id';
    }
}
