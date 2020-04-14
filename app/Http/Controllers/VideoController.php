<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Http\Services\VideoService;

class VideoController extends Controller
{
    /**
     * @param UploadVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::UploadVideoService($request);
    }

    /**
     * @param CreateVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(CreateVideoRequest $request)
    {
        return VideoService::CreateUploadedVideoService($request);
    }

    /**
     * @param UploadVideoBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadBanner(UploadVideoBannerRequest $request)
    {
        return VideoService::UploadVideoBannerService($request);
    }
}
