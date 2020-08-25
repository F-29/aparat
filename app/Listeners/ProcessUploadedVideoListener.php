<?php

namespace App\Listeners;

use App\Events\UploadVideo;
use App\Jobs\ConvertAndOrAddWaterMarkToUploadedVideoJob;

class ProcessUploadedVideoListener
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
        ConvertAndOrAddWaterMarkToUploadedVideoJob::dispatch($event->getVideo(), $event->getRequest()->video_id, $event->getRequest()->watermark, $event->getSlug());
    }
}
