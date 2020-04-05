<?php


namespace App\Http\Services;


use App\Category;
use App\Http\Requests\Category\GetAllCategoriesRequest;
use App\Http\Requests\Category\GetMyCategoriesRequest;

class CategoryService extends Service
{
    /**
     * @param GetAllCategoriesRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function getAllCategories(GetAllCategoriesRequest $request)
    {
        return response(Category::all(), 200);
    }

    /**
     * @param GetMyCategoriesRequest $request
     * @return mixed
     */
    public static function getMyCategories(GetMyCategoriesRequest $request)
    {
        return Category::where('user_id', auth()->user()->id)->get();
    }
}
