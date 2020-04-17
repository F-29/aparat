<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\GetAllTagsRequest;
use App\Http\Services\TagService;

class TagController extends Controller
{
    /**
     * @param GetAllTagsRequest $request
     * @return \App\Tag[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(GetAllTagsRequest $request)
    {
        return TagService::getAllTagsService($request);
    }

    /**
     * @param CreateTagRequest $request
     * @return \App\Tag|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function create(CreateTagRequest $request)
    {
        return TagService::createTagService($request);
    }
}
