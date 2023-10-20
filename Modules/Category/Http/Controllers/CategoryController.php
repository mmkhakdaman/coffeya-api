<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\OrderCategoryRequest;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    private function service(): CategoryService
    {
        return resolve(CategoryService::class);
    }

    private function repo(): CategoryRepository
    {
        return resolve(CategoryRepository::class);
    }


    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return CategoryResource::collection($this->repo()->getHasProductsCategories());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function adminList(): ResourceCollection
    {
        return CategoryResource::collection($this->repo()->getCategories(request()->query('with_product',false)));
    }

    /**
     * Store a newly created resource in storage.
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function create(CategoryRequest $request): CategoryResource
    {
        $category = $this->service()->createCategory($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, Category $category): CategoryResource
    {
        $this->service()->updateCategory($category, $request->validated());
        return new CategoryResource($category->fresh());
    }

    /**
     * Update the specified resource in storage.
     * @param OrderCategoryRequest $request
     * @return JsonResponse
     */
    public function reorder(OrderCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->service()->reorderCategories($request->input('categories'));
        return response()->json(['message' => 'Categories reordered successfully']);
    }

    /**
     * Update the specified resource in storage.
     * @param Category $category
     * @return JsonResponse
     */
    public function disable(Category $category): JsonResponse
    {
        $this->service()->disableCategory($category);
        return response()->json(
            [
                'message' => 'Category disabled successfully',
                'category' => new CategoryResource($category->fresh()),
            ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Category $category
     * @return JsonResponse
     */
    public function enable(Category $category): JsonResponse
    {
        $this->service()->enableCategory($category);
        return response()->json(
            [
                'message' => 'Category enabled successfully',
                'category' => new CategoryResource($category->fresh()),
            ]);
    }
}
