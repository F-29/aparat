<?php


namespace App\Http\Services;


use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\GetAllTagsRequest;
use App\Tag;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagService extends Service
{
    /**
     * @param GetAllTagsRequest $request
     * @return Tag[]|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public static function getAllTagsService(GetAllTagsRequest $request)
    {
        try {
            return Tag::all();
        } catch (Exception $exception) {
            Log::error('TagService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param CreateTagRequest $request
     * @return Tag|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public static function createTagService(CreateTagRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $tag = Tag::create($data);
            DB::commit();
            return response(['message' => 'success', 'data' => $tag], 201);
        } catch (Exception $exception) {
            Log::error('TagService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
