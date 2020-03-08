<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChannelUpdateRequest;
use App\Http\Services\ChannelService;

class ChannelController extends Controller
{
    /**
     * @param ChannelUpdateRequest $request
     * @return array
     */
    public function update(ChannelUpdateRequest $request)
    {
        dd("inja");
        $request = ChannelService::updateChannelInfo($request);

        return $request->validated();
    }
}
