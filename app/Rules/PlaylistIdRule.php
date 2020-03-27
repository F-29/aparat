<?php

namespace App\Rules;

use App\Playlist;
use Illuminate\Contracts\Validation\Rule;

class PlaylistIdRule implements Rule
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
        return Playlist::where(['id' => $value, 'user_id' => auth()->user()->id])->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid playlist id';
    }
}
