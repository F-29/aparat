<?php

namespace App\Jobs;

use App\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Video $video
     */
    private $video;
    /**
     * @var int
     */
    private $user_id;
    /**
     * @var string
     */
    private $user_name;
    /**
     * @var string
     */
    private $video_id;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var boolean
     */
    private $enable_watermark;

    private $videoFile;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $video_id
     * @param string $slug
     */
    private function __construct(Video $video, string $video_id, bool $watermark, string $slug)
    {
        $this->video = $video;
        $this->user_id = auth()->id();
        $this->user_name = auth()->user()->name;
        $this->video_id = $video_id;
        $this->enable_watermark = $watermark;
        $this->slug = $slug;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uploadedVideoPath = DIRECTORY_SEPARATOR . env('VIDEO_TMP_DIR') . DIRECTORY_SEPARATOR . $this->video_id;
        $uploadedTempVideo = FFMpegFacade::fromDisk('videos')
            ->open($uploadedVideoPath);
        if ($this->enable_watermark) {
            $filter = create_watermark($this->user_name . '/' . $this->slug);
            $this->videoFile = $uploadedTempVideo
                ->addFilter($filter);
        }
        $this->videoFile = $uploadedTempVideo
            ->export()
            ->toDisk('videos')
            ->inFormat(new X264('libmp3lame'));

        $this->videoFile->save($this->user_name . '/' . $this->slug . '.mp4');
        Storage::disk('videos')->delete($uploadedVideoPath);

        $this->video->duration = $uploadedTempVideo->getDurationInSeconds();
        $this->video->state = Video::STATE_CONVERTED;
        $this->video->save();
    }
}
