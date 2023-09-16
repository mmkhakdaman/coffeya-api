<?php

namespace Modules\Category\Services;

use Modules\Category\Entities\Category;
use Modules\Category\Repositories\CategoryRepository;

class CategoryService
{
    private function repo(): CategoryRepository
    {
        return resolve(CategoryRepository::class);
    }

    public function categories()
    {
        return $this->repo()->getCategories();
    }

    public function getHasProductsCategories()
    {
        return $this->repo()->getHasProductsCategories();
    }

    public function createCategory($data)
    {
        return $this->repo()->storeCategory(
            $data
        );
    }

    public function updateCategory(Category $category, array $data)
    {
        return $this->repo()->updateCategory($category, $data);
    }

    public function deleteCategory(Category $category)
    {
        return $this->repo()->delete($category);
    }

    public function reorderCategories($categories)
    {
        return $this->repo()->reorderCategories($categories);
    }
}
