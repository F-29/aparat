<?php


namespace App\Http\Services;


use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Playlist;
use App\Video;
use Exception;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;

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
        } catch (Exception $exception) {
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
            $uploadedVideoPath = DIRECTORY_SEPARATOR . env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR . $request->video_id;
            $video = FFMpegFacade::fromDisk('videos')
                ->open($uploadedVideoPath);
            $filter = new CustomFilter("drawtext=text='http\\://aparat.me': fontcolor=white@0.3: fontsize=23:
             box=1: boxcolor=white@0.0001: boxborderw=10: x=10: y=(h - text_h - 10)");
            $videoFile = $video
                ->addFilter($filter)
                ->export()
                ->toDisk('videos')
                ->inFormat(new X264('libmp3lame'));

            $video_duration = $video->getDurationInSeconds();
            $slug = Str::random(rand(6, 18));
            DB::beginTransaction();
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'channel_category_id' => $request->channel_category_id,
                'slug' => '',
                'info' => $request->info,
                'duration' => $video_duration,
                'banner' => '',
                'publish_at' => $request->publish_at,
                'commentable' => $request->commentable
            ]);
            $video->slug = $slug;
            $video->banner = $video->slug . '-banner';
            $video->save();

            $videoFile->save(auth()->id() . '/' . $video->slug . '.mp4');
            Storage::disk('videos')->delete($uploadedVideoPath);
            if ($request->banner) {
                Storage::disk('videos')->move(DIRECTORY_SEPARATOR . env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR
                    . $request->banner, md5(auth()->id()) . DIRECTORY_SEPARATOR . $video->banner);
            }

            if (!empty($request->playlist)) {
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }
            if (!empty($request->tags)) {
                $video->tags()->attach($request->tags);
            }
            DB::commit();
            return response(['message' => 'success', 'data' => $video], 200);
        } catch (Exception $exception) {
            dd($exception);
            DB::rollBack();
            Log::error("VideoService: " . $exception);
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
            Storage::disk('videos')->put(DIRECTORY_SEPARATOR . env('BANNER_DIR') .
                DIRECTORY_SEPARATOR . $fileName, $banner->get());

            return response(['message' => 'success', 'banner' => $fileName], 200);
        } catch (Exception $exception) {
            Log::error('VideoService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
