<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ProductResource;

class ProductController extends Controller
{
    public function list()
    {
        return ProductResource::collection(
            resolve(ProductService::class)->productList()
        );
    }
    public function activeProductList()
    {
        return ProductResource::collection(
            resolve(ProductService::class)->activeProductList()
        );
    }
}
