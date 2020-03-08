<?php


namespace App\Http\Services;


use App\Http\Requests\ChannelUpdateRequest;

class ChannelService
{
    /**
     * @param ChannelUpdateRequest $request
     * @return ChannelUpdateRequest
     */
    public static function updateChannelInfo(ChannelUpdateRequest $request)
    {
        $channelId = $request->route('id');
        // TODO: check weather if the is THE Admin or not
        $channel = auth()->user()->channel;
        $channel->name = $request->name;
        $channel->info = $request->info;
        $channel->save;

        return $request;
    }
}
