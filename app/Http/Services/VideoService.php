<?php


namespace App\Http\Services;


use App\Playlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Video;

use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;
use Pbmedia\LaravelFFMpeg\Media;

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
            Storage::disk('videos')->put('/tmp/' . $fileName, $video->get());

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
            /** @var Media $video_duration */
            $video_duration = FFMpegFacade::fromDisk('videos')->open('/tmp/' . $request->video_id);
            $video_duration = $video_duration->getDurationInSeconds();
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
            $slug = Str::random(rand(6, 18));
            /**
             * @var $video Video
             */
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'channel_category_id' => $request->channel_category_id,
                'slug' => '',
                'info' => $request->info,
                'duration' => $video_duration,
                'banner' => $request->banner,
                'publish_at' => $request->publish_at,
            ]);
            $video->slug = $slug;
            $video->banner = $video->slug . '-banner';
            $video->save();

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
            dd($exception);
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
            $banner = $request->file('banner');
            $fileName = md5(time()) . Str::random(10);
//            $path = public_path(env('BANNER_DIR'));
//            $banner->move($path, $fileName);
            Storage::disk('videos')->put('/tmp/' . $fileName, $banner->get());

            return response(['message' => 'success', 'banner' => $fileName], 200);
        } catch (\Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
