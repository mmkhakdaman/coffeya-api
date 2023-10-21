<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\OrderProductRequest;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ProductResource;

class ProductController extends Controller
{
    private function service(): ProductService
    {
        return resolve(ProductService::class);
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollectionAlias
     */
    public function list()
    {
        return ProductResource::collection(
            $this->service()->productList()
        );
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function activeProductList(): ResourceCollection
    {
        return ProductResource::collection(
            $this->service()->activeProductList()
        );
    }


    /**
     * Store a newly created resource in storage.
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function create(ProductRequest $request): ProductResource
    {
        $product = $this->service()->createProduct($request->validated());
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $this->service()->updateProduct($product, $request->validated());
        return new ProductResource($product->fresh());
    }

    /**
     * Update the specified resource in storage.
     * @param Product $product
     * @return ProductResource
     */
    public function toggleActive(Product $product): ProductResource
    {
        $this->service()->toggleActive($product);
        return new ProductResource($product->fresh());
    }

    /**
     * Update the specified resource in storage.
     * @param Product $product
     * @return ProductResource
     */
    public function toggleStock(Product $product)
    {
        $this->service()->toggleStock($product);
        return new ProductResource($product->fresh());
    }

    /**
     * Update the specified resource in storage.
     * @param OrderProductRequest $request
     * @return JsonResponse
     */
    public function reorder(OrderProductRequest $request)
    {
        $this->service()->reorder($request->input('products'));
        return response()->json(['message' => 'Reordered successfully']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return JsonResponse
     */
    public function delete(Product $product): JsonResponse
    {
        $this->service()->deleteProduct($product);
        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Display the specified resource.
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
