<?php


namespace App\Http\Services;


use App\Http\Requests\UploadVideoRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService extends Service
{
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
}
