<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\GetAllCategoriesRequest;
use App\Http\Requests\Category\GetMyCategoriesRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Http\Services\CategoryService;

class CategoryController extends Controller
{
    /**
     * @param GetAllCategoriesRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function all(GetAllCategoriesRequest $request)
    {
        return CategoryService::getAllCategoriesService($request);
    }

    /**
     * @param GetMyCategoriesRequest $request
     * @return mixed
     */
    public function myCategories(GetMyCategoriesRequest $request)
    {
        return CategoryService::getMyCategoriesService($request);
    }

    public function create(CreateCategoryRequest $request)
    {
        return CategoryService::createCategoryService($request);
    }

    /**
     * @param UploadCategoryBannerRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function uploadBanner(UploadCategoryBannerRequest $request)
    {
        return CategoryService::uploadCategoryBannerService($request);
    }
}
