<?php


namespace App\Http\Services;


use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use Illuminate\Support\Facades\Log;
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
            $path = public_path(env('VIDEO_DIR'));
            $video->move($path, $fileName);

            return response(['message' => 'success', 'video' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    public static function CreateUploadedVideoService(CreateVideoRequest $request)
    {
        dd($request->all());
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
