<?php


namespace App\Http\Services;


use App\Playlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Video;

use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;

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

    /**
     * @param CreateVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function CreateUploadedVideoService(CreateVideoRequest $request)
    {
        try {
            DB::beginTransaction();
            $video_dir = right_dir_separator(public_path(env('VIDEO_DIR')));
            $tmp_video_dir = right_dir_separator(public_path(env('VIDEO_TMP_DIR')));
            if (!file_exists(public_path(env('VIDEO_DIR'))) && !is_dir(public_path(env('VIDEO_DIR')))) {
                mkdir($video_dir);
            }
            File::move(
                $tmp_video_dir . DIRECTORY_SEPARATOR . $request->video_id,
                $video_dir . DIRECTORY_SEPARATOR . $request->video_id
            );
            /**
             * @var $video Video
             */
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'channel_category_id' => $request->channel_category_id,
                'slug' => $request->video_id, // TODO: calculate slug
                'info' => $request->info,
                'duration' => 0, // TODO: get video duration
                'banner' => $request->banner,
                'publish_at' => $request->publish_at,
            ]);
            if (!empty($request->playlist)) {
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }
            if (!empty($request->tags)) {
                $video->tags()->attach($request->tags);
            }
            DB::commit();
//            DB::rollBack();
            return response(['message' => 'success', 'data' => $video], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("error in CreateUploadedVideoService " . $exception);
            return response(['message' => 'there was an error'], 500);
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
