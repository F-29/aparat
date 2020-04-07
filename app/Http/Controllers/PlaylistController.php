<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAllPlaylistsRequest;
use App\Http\Requests\GetMyPlaylistsRequest;
use App\Http\Services\PlaylistService;

class PlaylistController extends Controller
{
    /**
     * @param GetAllPlaylistsRequest $request
     * @return \App\Playlist[]|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function all(GetAllPlaylistsRequest $request)
    {
        return PlaylistService::getAllPlaylists($request);
    }

    public function myPlaylists(GetMyPlaylistsRequest $request)
    {
        return PlaylistService::getMyPlaylists($request);
    }
}
