<?php

namespace Modules\Table\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Table\Entities\Table;
use Modules\Table\Http\Requests\TableRequest;
use Modules\Table\Services\TableService;
use Modules\Table\Transformers\TableResource;

class TableController extends Controller
{

    private function service()
    {
        return resolve(TableService::class);
    }


    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TableResource::collection(Table::all());
    }

    /**
     * Store a newly created resource in storage.
     * @param TableRequest $request
     * @return TableResource
     */
    public function store(TableRequest $request): TableResource
    {
        $table = $this->service()->createTable($request->validated());
        $table->refresh();
        return new TableResource($table);
    }

    /**
     * Show the specified resource.
     * @param Table $table
     * @return TableResource
     */
    public function show(Table $table): TableResource
    {
        return new TableResource($table);
    }


    /**
     * Update the specified resource in storage.
     * @param TableRequest $request
     * @param Table $table
     * @return TableResource
     */
    public function update(TableRequest $request, Table $table): TableResource
    {
        $this->service()->updateTable($request->validated(), $table);

        return new TableResource($table->fresh());
    }

    /**
     * toggle active the specified resource from storage.
     * @param Table $table
     * @return TableResource
     */
    public function toggleActive(Table $table): TableResource
    {
        $this->service()->toggleActive($table);

        return new TableResource($table->fresh());
    }
}
