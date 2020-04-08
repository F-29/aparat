<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\GetAllPlaylistsRequest;
use App\Http\Requests\Playlist\GetMyPlaylistsRequest;
use App\Http\Services\PlaylistService;

class PlaylistController extends Controller
{
    /**
     * @param GetAllPlaylistsRequest $request
     * @return \App\Playlist[]|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function all(GetAllPlaylistsRequest $request)
    {
        return PlaylistService::getAllPlaylistsService($request);
    }

    /**
     * @param GetMyPlaylistsRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function myPlaylists(GetMyPlaylistsRequest $request)
    {
        return PlaylistService::getMyPlaylistsService($request);
    }

    /**
     * @param CreatePlaylistRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(CreatePlaylistRequest $request)
    {
        return PlaylistService::createPlaylistService($request);
    }
}
