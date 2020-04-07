<?php


namespace App\Http\Services;


use App\Http\Requests\GetAllPlaylistsRequest;
use App\Http\Requests\GetMyPlaylistsRequest;
use App\Playlist;
use Exception;
use Illuminate\Support\Facades\Log;

class PlaylistService extends Service
{
    /**
     * @param GetAllPlaylistsRequest $request
     * @return Playlist[]|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public static function getAllPlaylists(GetAllPlaylistsRequest $request)
    {
        try {
            return Playlist::all();
        } catch (Exception $exception) {
            Log::error('PlaylistService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param GetMyPlaylistsRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function getMyPlaylists(GetMyPlaylistsRequest $request)
    {
        try {
            return auth()->user()->playlists;
        } catch (Exception $exception) {
            Log::error('PlaylistService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
