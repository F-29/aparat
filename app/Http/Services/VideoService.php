<?php


namespace App\Http\Services;


use App\Events\UploadVideo;
use App\Http\Requests\SetStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Playlist;
use App\Video;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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
            $slug = Str::random(rand(6, 18));

            DB::beginTransaction();
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'channel_category_id' => $request->channel_category_id,
                'slug' => '',
                'info' => $request->info,
                'duration' => 1,
                'banner' => '',
                'publish_at' => $request->publish_at,
                'state' => Video::STATE_PENDING,
                'commentable' => $request->commentable
            ]);
            $video->slug = $slug;
            $video->banner = $video->slug . '-banner';
            $video->save();

            event(new UploadVideo($video, $request, $slug));
            if ($request->banner) {
                Storage::disk('videos')->move(DIRECTORY_SEPARATOR . env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR
                    . $request->banner, auth()->user()->name . DIRECTORY_SEPARATOR . $video->banner);
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

    public static function setState(SetStateVideoRequest $request)
    {
        $video = Video::where('slug', $request->slug)->first();
        if (empty($video)) throw new ModelNotFoundException('Video Not Found');
        $video->state = $request->validated()['state'];
        $video->save();
        return response($video, 202);
    }
}
