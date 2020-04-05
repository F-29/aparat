<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\GetAllCategoriesRequest;
use App\Http\Requests\Category\GetMyCategoriesRequest;
use App\Http\Services\CategoryService;

class CategoryController extends Controller
{
    /**
     * @param GetAllCategoriesRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function all(GetAllCategoriesRequest $request)
    {
        return CategoryService::getAllCategories($request);
    }

    /**
     * @param GetMyCategoriesRequest $request
     * @return mixed
     */
    public function myCategories(GetMyCategoriesRequest $request)
    {
        return CategoryService::getMyCategories($request);
    }
}
