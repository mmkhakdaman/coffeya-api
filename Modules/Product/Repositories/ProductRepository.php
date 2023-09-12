<?php

namespace Modules\Product\Repositories;

use Modules\Product\Entities\Product;

class ProductRepository
{
    public function storeProduct($data)
    {
        $order = $this->query()->max('order');
        $data['order'] = $order + 1;
        return $this->query()->create($data);
    }

    private function query()
    {
        return Product::query();
    }

    public function updateProduct(Product $category, array $data)
    {
        return $category->update($data);
    }

    public function delete(Product $category)
    {
        return $category->delete();
    }

    public function activeProducts()
    {
        return $this->query()
            ->orderBy('order')
            ->where('is_active', 1)
            ->get();
    }

    public function findProductsByIds($ids)
    {
        return $this->query()
            ->whereIn('id', $ids)
            ->get();
    }
}
