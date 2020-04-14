<?php

namespace App\Rules\Unique;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueForUserRule implements Rule
{
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var string|null
     */
    private $columnName;
    /**
     * @var string|null
     */
    private $userId;
    /**
     * @var string
     */
    private $userIdField;

    /**
     * Create a new rule instance.
     *
     * @param string $tableName The table with the unique column
     * @param string|null $columnName The unique column's name
     * @param string|null $userId The user's id (if empty => current logged in user's id)
     * @param string|null $userIdField The column that contains user id
     */
    public function __construct(string $tableName, string $columnName = null, string $userId = null, string $userIdField = 'user_id')
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->userId = $userId ?? auth()->id();
        $this->userIdField = $userIdField;
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
        $field = !empty($this->columnName) ? $this->columnName : $attribute;

        // if there is no row in the column with this value it will pass
        return DB::table($this->tableName)
                ->where($field, $value)
                ->where($this->userIdField, $this->userId)
                ->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $field = $this->columnName ?? 'field';
        return 'This ' . $field . ' already exists for this user';
    }
}
