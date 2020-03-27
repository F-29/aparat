<?php


namespace App\Http\Services;


use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService extends Service
{
    /**
     * @param UploadVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function UploadVideoService(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName = md5(time()) . Str::random(10);
            $path = public_path(env('VIDEO_TMP_DIR'));
            $video->move($path, $fileName);

            return response(['message' => 'success', 'video' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    public static function CreateUploadedVideoService(CreateVideoRequest $request)
    {

        $video_dir = right_dir_separator(public_path(env('VIDEO_DIR')));
        $tmp_video_dir = right_dir_separator(public_path(env('VIDEO_TMP_DIR')));

        try {
            if (!file_exists(public_path(env('VIDEO_DIR'))) && !is_dir(public_path(env('VIDEO_DIR')))) {
                mkdir($video_dir);
            }
            $aa = File::move(
                public_path(env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR . $request->video_id),
                public_path(env('VIDEO_DIR') . DIRECTORY_SEPARATOR . $request->video_id)
            );
            dd($request->all(), $aa);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param UploadVideoBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function UploadVideoBannerService(UploadVideoBannerRequest $request)
    {
        try {
            $video = $request->file('banner');
            $fileName = md5(time()) . Str::random(10);
            $path = public_path(env('BANNER_DIR'));
            $video->move($path, $fileName);

            return response(['message' => 'success', 'banner' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
