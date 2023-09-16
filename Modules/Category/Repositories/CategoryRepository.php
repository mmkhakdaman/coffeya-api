<?php

namespace Modules\Category\Repositories;

use Modules\Category\Entities\Category;

class CategoryRepository
{


    public function storeCategory($data)
    {
        $order = $this->query()->max('order');
        $data['order'] = $order + 1;
        return $this->query()->create($data);
    }

    private function query()
    {
        return Category::query();
    }

    public function updateCategory(Category $category, array $data)
    {
        return $category->update($data);
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }

    public function getCategories()
    {
        return $this->query()
            ->orderBy('order')
            ->get();
    }

    public function getHasProductsCategories()
    {
        return $this->query()
            ->has('products')
            ->orderBy('order')
            ->get();
    }

    public function reorderCategories($categories)
    {
        foreach ($categories as $category) {
            $this->query()->find($category['id'])->update([
                'order' => $category['order'],
            ]);
        }
    }
}
