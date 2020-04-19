<?php


namespace App\Http\Services;


use App\Channel;
use App\Http\Requests\channel\ChannelUpdateRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadChannelBannerRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChannelService extends Service
{
    /**
     * @param ChannelUpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|ChannelUpdateRequest
     * @throws AuthorizationException
     */
    public static function updateChannelInfoService(ChannelUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            // WARNING: [you must pass the channel id NOT user_id or user table's id]
            if ($channelId = $request->route('id')) {
                $channel = Channel::findOrFail($channelId);
                $user = $channel->user;
            } else {
                $channel = auth()->user()->channel;
                $user = auth()->user();
            }
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

    /**
     * @param UploadChannelBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function uploadChannelBannerService(UploadChannelBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $channel = auth()->user()->channel;
            $fileName = md5(auth()->id()) . '-' . Str::random(15);
            Storage::disk('channel')->put($fileName, $banner->get());

            if ($channel->banner) {
                Storage::disk('channel')->delete($channel->banner);
            }
            $channel->banner = Storage::disk('channel')->path($fileName);
            $channel->save();

            return response([
                'banner' => Storage::disk('channel')->url($fileName)
            ], 200);
        } catch (\Exception $exception) {
            Log::error('ChannelService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param UpdateSocialsRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function updateSocialsService(UpdateSocialsRequest $request)
    {
        try {
            $socials = [
                'cloob' => $request->input('cloob'),
                'lenzor' => $request->input('lenzor'),
                'instagram' => $request->input('instagram'),
                'whatsapp' => $request->input('whatsapp'),
                'facebook' => $request->input('facebook'),
                'twitter' => $request->input('twitter'),
                'telegram' => $request->input('telegram'),
            ];
            auth()->user()->channel->update(['socials' => $socials]);

            return response(['message' => 'success'], 200);
        } catch (\Exception $exception) {
            Log::error('ChannelService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
