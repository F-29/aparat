<?php

namespace App\Rules\Id;

use App\Category;
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->categoryType === self::PUBLIC_CATEGORIES) {
            return Category::where('id', $value)->whereNull('user_id')->count();
        }

        if ($this->categoryType === self::PRIVATE_CATEGORIES) {
            return Category::where('id', $value)->where('user_id', auth()->id())->count();
        }

        return Category::where('id', $value)->count();
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
