<?php

namespace Modules\Product\Services;

use Modules\Product\Entities\Product;
use Modules\Product\Repositories\ProductRepository;

class ProductService
{
    private function repo(): ProductRepository
    {
        return resolve(ProductRepository::class);
    }

    public function productList()
    {
        return $this->repo()->activeProducts();
    }
    
    public function activeProductList()
    {
        return $this->repo()->activeProducts();
    }


    public function createProduct($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->repo()->storeProduct(
            $data
        );
    }

    public function updateProduct(Product $category, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }
        return $this->repo()->updateProduct($category, $data);
    }

    public function deleteProduct(Product $category)
    {
        return $this->repo()->delete($category);
    }
    
    public function toggleActive(Product $product)
    {
        return $this->repo()->toggleActive($product);
    }

    public function toggleStock(Product $product)
    {
        return $this->repo()->toggleStock($product);
    }

    public function reorder($data)
    {
        return $this->repo()->reorder($data);
    }

    
}
