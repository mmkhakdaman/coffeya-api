<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\ProductRepository;

class ProductService
{
    private function repo(): ProductRepository
    {
        return resolve(ProductRepository::class);
    }

    public function productList(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo()->products();
    }

    public function activeProductList(): \Illuminate\Database\Eloquent\Collection|array
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

    public function updateProduct(Product $product, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }
        return $this->repo()->updateProduct($product, $data);
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

    public function uploadImage(Product $product, array|\Illuminate\Http\UploadedFile|null $file)
    {
        if (isset($file)) {
            Storage::disk('public')->delete($product->image);

            $file = $file->store('products', 'public');
        }
        return $this->repo()->updateProduct($product, ['image' => $file]);
    }

    public function removeImage(Product $product)
    {
        Storage::disk('public')->delete($product->image);
        return $this->repo()->updateProduct($product, ['image' => null]);
    }

}
