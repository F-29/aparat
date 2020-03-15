<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Requests\UploadVideoRequest;
use App\Http\Services\VideoService;

class VideoController extends Controller
{
    /**
     * @param UploadVideoRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::UploadVideo($request);
    }

    public function create(CreateVideoRequest $request)
    {
        return VideoService::CreateUploadedVideo($request);
    }
}
