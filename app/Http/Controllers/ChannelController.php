<?php

namespace App\Http\Controllers;

use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Services\ChannelService;

class ChannelController extends Controller
{
    /**
     * @param ChannelUpdateRequest $request
     * @return ChannelUpdateRequest|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ChannelUpdateRequest $request)
    {
         return ChannelService::updateChannelInfo($request);

    }
}
