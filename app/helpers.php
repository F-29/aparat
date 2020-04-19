<?php

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\User;
use FFMpeg\Filters\Video\CustomFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * @return string
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

if (!function_exists('fix_http_slashes')) {

    /**
     * @param string $str
     * @return string
     */
    function fix_http_slashes(string $str)
    {
        return str_replace('http://', "http\\://", $str);
    }
}

if (!function_exists('create_watermark')) {

    function create_watermark(string $url)
    {
        $url = fix_http_slashes(env('APP_URL')) . $url;
        return new CustomFilter("drawtext=text='" . $url . "': fontcolor=white@0.3: fontsize=23:
             box=1: boxcolor=white@0.0001: boxborderw=10: x=10: y=(h - text_h - 10)");
    }
}

if (!function_exists('clear_storage')) {
    /**
     * @param string $storageName
     * @return bool
     */
    function clear_storage(string $storageName)
    {
        try {
            Storage::disk($storageName)->delete(Storage::disk($storageName)->allFiles());
            foreach (Storage::disk($storageName)->allDirectories() as $dir) {
                Storage::disk($storageName)->deleteDirectory($dir);
            }
            return true;
        } catch (Exception $exception) {
            Log::error("error in clear_storage helper: " . $exception);
            return false;
        }
    }
}
