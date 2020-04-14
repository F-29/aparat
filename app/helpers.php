<?php

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

if (!function_exists('to_valid_mobile_number')) {
    /** returns a valid phone number that is suitable to be saved in database
     * @param string $mobile
     * @return string
     */
    function to_valid_mobile_number(string $mobile)
    {
        return $mobile = '+98' . substr($mobile, -10, 10);
    }
}

if (!function_exists('user_request_mistake_finder')) {

    /**
     * @param User $user
     * @param int $code
     * @throws UserAlreadyExistsException
     * @throws WrongVerifyCodeException
     * @throws ModelNotFoundException
     */
    function user_request_mistake_finder(User $user, int $code)
    {
        if ($code !== $user->verify_code) {
            throw new WrongVerifyCodeException();
        }

        if (empty($user)) {
            throw new ModelNotFoundException('user have already been verified');
        }

        if (!empty($user->verified_at)) {
            throw new UserAlreadyExistsException('user already exists');
        }
    }
}

if (!function_exists('random_verification_code')) {
    /**
     * @return int
     * @throws Exception
     */
    function random_verification_code()
    {
        try {
            return random_int(111111, 999999);
        } catch (Exception $exception) {
            Log::error('there was an error in Helper functions(random_verification_code):\n' . $exception);
        }
    }
}

if (!function_exists('right_dir_separator')) {
    /**
     * @param $dir
     * @param bool $is_web
     * @return string|string[]
     */
    function right_dir_separator($dir, bool $is_web = false)
    {
        if ($is_web) {
            return str_replace('\\', "/", $dir);
        }

        if (strpos(PHP_OS, 'WIN') !== false) {
            $dir = str_replace('/', "\\", $dir);
        } else {
            $dir = str_replace('\\', "/", $dir);
        }

        return $dir;
    }
}

if (!function_exists('randomize_for_id')) {
    /**
     * @param int $id
     * @return int
     */
    function randomize_for_id(int $id)
    {
        return ($id * 1000) + 33 + (6000 + 200 + 70 + 1);
    }
}

if (!function_exists('derandomize_for_id')) {
    /**
     * @param int $id
     * @return int
     */
    function derandomize_for_id(int $id)
    {
        return (int)($id - 33 - 6000 - 200 - 70 - 1) / 1000;
    }
}
