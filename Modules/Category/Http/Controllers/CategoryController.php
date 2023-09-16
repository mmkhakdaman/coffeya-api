<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\OrderCategoryRequest;
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

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function adminList()
    {
        return  CategoryResource::collection($this->service()->categories());
    }

    /**
     * Store a newly created resource in storage.
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function create(CategoryRequest $request)
    {
        $category = $this->service()->createCategory($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryRequest $request
     * @param Category $id
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->service()->updateCategory($category, $request->validated());
        return new CategoryResource($category->fresh());
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryRequest $request
     * @return Response
     */
    public function reorder(OrderCategoryRequest $request)
    {
        $this->service()->reorderCategories($request->input('categories'));
        return response()->json(['message' => 'Categories reordered successfully']);
    }
}
