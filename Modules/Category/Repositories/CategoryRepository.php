<?php

namespace Modules\Category\Repositories;

use Modules\Category\Entities\Category;

class CategoryRepository
{

    private function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Category::query();
    }

    public function storeCategory($data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $order = $this->query()->max('order');
        $data['order'] = $order + 1;
        return $this->query()->create($data);
    }


    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }

    public function getCategories(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->query()
            ->orderBy('order')
            ->withWhereHas('activeProducts')
            ->get();
    }

    public function getHasProductsCategories(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->query()
            ->has('products')
            ->orderBy('order')
            ->get();
    }

    public function reorderCategories($categories): void
    {
        foreach ($categories as $category) {
            $this->query()->find($category['id'])->update([
                'order' => $category['order'],
            ]);
        }
    }

    public function disableCategory(Category $category): bool
    {
        return $category->update([
            'is_active' => false,
        ]);
    }

    public function enableCategory(Category $category): bool
    {
        return $category->update([
            'is_active' => true,
        ]);
    }
}
