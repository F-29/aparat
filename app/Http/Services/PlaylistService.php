<?php


namespace App\Http\Services;


use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\GetAllPlaylistsRequest;
use App\Http\Requests\Playlist\GetMyPlaylistsRequest;
use App\Playlist;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaylistService extends Service
{
    /**
     * @param GetAllPlaylistsRequest $request
     * @return Playlist[]|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public static function getAllPlaylistsService(GetAllPlaylistsRequest $request)
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
    public static function getMyPlaylistsService(GetMyPlaylistsRequest $request)
    {
        try {
            return auth()->user()->playlists;
        } catch (Exception $exception) {
            Log::error('PlaylistService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param CreatePlaylistRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function createPlaylistService(CreatePlaylistRequest $request)
    {
        try {
            DB::beginTransaction();
            $res = Playlist::create([
                'title' => $request->validated()['title'],
                'user_id' => auth()->id()
            ]);
            DB::commit();
            return $res;
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
            Log::error('PlaylistService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
