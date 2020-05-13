<?php

namespace App\Listeners;

use App\Events\UploadVideo;
use App\Jobs\ConvertAndAddWaterMarkToUploadedVideoJob;

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
        ConvertAndAddWaterMarkToUploadedVideoJob::dispatch($event->getVideo(), $event->getRequest()->video_id, $event->getSlug());
    }
}
