<?php

namespace Modules\Table\Services;

use Modules\Table\Entities\Table;
use Modules\table\Repositories\TableRepository;

class TableService
{
    private function repo(): TableRepository
    {
        return resolve(TableRepository::class);
    }

    public function createTable(array $data) : Table
    {
        return $this->repo()->create($data);
    }

    public function updateTable(array $data, Table $table)
    {
        return $this->repo()->update($data, $table);
    }

    public function toggleActive(Table $table)
    {
        return $this->repo()->toggleActive($table);
    }
}
