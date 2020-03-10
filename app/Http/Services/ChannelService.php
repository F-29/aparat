<?php


namespace App\Http\Services;


use App\channel;
use App\Http\Requests\channel\ChannelUpdateRequest;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChannelService
{
    /**
     * @param ChannelUpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|ChannelUpdateRequest
     * @throws AuthorizationException
     */
    public static function updateChannelInfo(ChannelUpdateRequest $request)
    {
        if ($channelId = $request->route('id')) {
            $channel = channel::findOrFail($channelId);
            $user = $channel->user;
        } else {
            $channel = auth()->user()->channel;
            $user = auth()->user();
        }
        try {
            DB::beginTransaction();
            if (empty($request->name) && empty($request->info) && empty($request->website)) {
                return response(["message" => "no data to update from"], 200, ["NO_CHANGES" => 0]);
            }
            $channel->name = empty($request->name) ? $channel->name : $request->name;
            $channel->info = empty($request->info) ? $channel->info : $request->info;
            $channel->save();

            $user->website = empty($request->website) ? $channel->website : $request->website;
            $user->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception instanceof AuthorizationException) {
                throw $exception;
            }
            Log::error($exception);
        }
        return response(["message" => "updated"], 200, ["CHANGES" => 1]);
    }
}
