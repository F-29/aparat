<?php


namespace App\Http\Services;


use App\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\GetAllCategoriesRequest;
use App\Http\Requests\Category\GetMyCategoriesRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService extends Service
{
    /**
     * @param GetAllCategoriesRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function getAllCategoriesService(GetAllCategoriesRequest $request)
    {
        try {
            return response(Category::all(), 200);
        } catch (Exception $exception) {
            Log::error('CategoryService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param GetMyCategoriesRequest $request
     * @return mixed
     */
    public static function getMyCategoriesService(GetMyCategoriesRequest $request)
    {
        try {
            return auth()->user()->categories;
        } catch (Exception $exception) {
            Log::error('CategoryService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    public static function createCategoryService(CreateCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $category = auth()->user()->categories()->create($data);
            if (!empty($request->banner)) {
                $bannerPath = randomize_for_id(auth()->id()) . '/' . $request->banner;
                Storage::disk('category')->move(env('BANNER_DIR') . DIRECTORY_SEPARATOR . $request->banner, $bannerPath);
            }
            DB::commit();

            return response(['message' => 'success', 'data' => $category], 200);
        } catch (Exception $exception) {
            dd($exception);
            DB::rollBack();
            Log::error('CategoryService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }

    /**
     * @param UploadCategoryBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function uploadCategoryBannerService(UploadCategoryBannerRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = md5(time()) . Str::random(10);
            Storage::disk('category')->put(DIRECTORY_SEPARATOR . env('BANNER_DIR') .
                DIRECTORY_SEPARATOR . $fileName, $banner->get());

            return response(['message' => 'success', 'banner' => $fileName], 200);
        } catch (Exception $exception) {
            Log::error('CategoryService: ' . $exception);
            return response(['message' => 'there was an error'], 500);
        }
    }
}
