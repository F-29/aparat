<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CategoryIdRule implements Rule
{
    const PUBLIC_CATEGORIES = "public";
    const PRIVATE_CATEGORIES = "private";
    const ALL_CATEGORIES = "all";
    private $categoryType;

    /**
     * Create a new rule instance.
     *
     * @param string $categoryType
     */
    public function __construct($categoryType = self::ALL_CATEGORIES)
    {
        $this->categoryType = $categoryType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        dd($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid category.';
    }
}
