<?php

namespace App\Http\Controllers;

use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadChannelBannerRequest;
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

    /**
     * @param UploadChannelBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadBanner(UploadChannelBannerRequest $request)
    {
        return ChannelService::uploadChannelBanner($request);
    }

    /**
     * @param UpdateSocialsRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateSocials(UpdateSocialsRequest $request)
    {
        return ChannelService::updateSocials($request);
    }
}
