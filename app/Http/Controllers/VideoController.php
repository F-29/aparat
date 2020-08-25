<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\ListVideosRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\SetStateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Http\Services\VideoService;

class VideoController extends Controller
{
    /**
     * @param ListVideosRequest $request
     * @return mixed
     */
    public function list(ListVideosRequest $request)
    {
        return VideoService::ListUsersAllVideosService($request);
    }

    /**
     * @param ListVideosRequest $request
     * @return mixed
     */
    public function listRepublished(ListVideosRequest $request)
    {
        return VideoService::ListMyRepublishingVideosService($request);
    }

    /**
     * @param ListVideosRequest $request
     * @return mixed
     */
    public function listMyVideos(ListVideosRequest $request)
    {
        return VideoService::ListMySelfVideosService($request);
    }

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

    /**
     * @param RepublishVideoRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function republish(RepublishVideoRequest $request)
    {
        // PANIC: it returns a 404 if slug is wrong
        return VideoService::republishVideoService($request);
    }

    /**
     * @param SetStateVideoRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setState(SetStateVideoRequest $request)
    {
        return VideoService::setStateService($request);
    }

    public function like(LikeVideoRequest $request)
    {
        return VideoService::likeOrDislikeVideoService($request);
    }
}
