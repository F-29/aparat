<?php

namespace App\Listeners;

use App\Events\UploadVideo;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;

class ProcessUploadedVideo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UploadVideo $event
     * @return void
     */
    public function handle(UploadVideo $event)
    {
        $video = $event->getVideo();
        $uploadedVideoPath = DIRECTORY_SEPARATOR . env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR . $event->getRequest()->video_id;
        $uploadedTempVideo = FFMpegFacade::fromDisk('videos')
            ->open($uploadedVideoPath);
        $filter = create_watermark(auth()->user()->name . '/' . $event->getVideo()->slug);
        $videoFile = $uploadedTempVideo
            ->addFilter($filter)
            ->export()
            ->toDisk('videos')
            ->inFormat(new X264('libmp3lame'));

        $videoFile->save(auth()->id() . '/' . $video->slug . '.mp4');
        Storage::disk('videos')->delete($uploadedVideoPath);

        $video->duration = $uploadedTempVideo->getDurationInSeconds();
        $video->save();

    }
}
