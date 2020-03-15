<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadVideoRequest;
use App\Http\Services\VideoService;

class VideoController extends Controller
{
    public function upload(UploadVideoRequest $request)
    {
        return VideoService::UploadVideo($request);
    }
}
