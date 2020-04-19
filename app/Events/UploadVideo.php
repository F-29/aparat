<?php

namespace App\Events;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Video
     */
    private $video;
    /**
     * @var CreateVideoRequest
     */
    private $request;

    /**
     * UploadVideo constructor.
     * @param Video $video
     * @param CreateVideoRequest $request
     * @param string $slug
     */
    public function __construct(Video $video, CreateVideoRequest $request)
    {
        $this->video = $video;
        $this->request = $request;
    }

    /**
     * @return CreateVideoRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
