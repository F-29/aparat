<?php


namespace App\Http\Services;


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
    public static function UploadVideo(UploadVideoRequest $request)
    {
        try {
            $video = $request->file('video');
            $fileName = time() . Str::random(10);
            $path = public_path('videos' . DIRECTORY_SEPARATOR . 'tmp');
            $video->move($path, $fileName);

            return response(['message' => 'success', 'video' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    public static function CreateUploadedVideo(CreateVideoRequest $request)
    {

    }
}
