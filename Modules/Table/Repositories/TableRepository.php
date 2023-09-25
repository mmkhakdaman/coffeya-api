<?php

namespace Modules\Table\Repositories;

use Modules\Table\Entities\Table;
use Illuminate\Support\Str;

class TableRepository
{
    public function create(array $data): Table
    {
        $data['token'] = $this->generateToken();
        return Table::create($data);
    }

    public function update(array $data, Table $table)
    {
        $table->update($data);
        return $table;
    }

    public function toggleActive(Table $table)
    {
        $table->update(['active' => !$table->active]);
        return $table;
    }

    private function generateToken(): string
    {
        $token = Str::random(10);
        if (Table::where('token', $token)->exists()) {
            return $this->generateToken();
        }
        return $token;
    }
}
