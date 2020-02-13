<?php

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    return random_int(111111, 999999);
}
}
