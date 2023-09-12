<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    private function service(): CategoryService
    {
        return resolve(CategoryService::class);
    }
    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function list()
    {
        return  CategoryResource::collection($this->service()->categories());
    }
}
