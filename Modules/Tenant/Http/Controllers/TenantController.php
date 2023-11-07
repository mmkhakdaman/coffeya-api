<?php

namespace Modules\Tenant\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Http\Requests\TenantRequest;
use Modules\Tenant\Transformers\TenantResource;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $tenants = Tenant::query()
            ->with('domain')
            ->where('name', 'like', "%{$request->name}%")->get();
        return TenantResource::collection($tenants);
    }

    /**
     * Update the specified resource in storage.
     * @param TenantRequest $request
     * @return TenantResource
     */
    public function update(TenantRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('tenant/logo');
        }

        tenant()->update($data);

        $tenant = tenant()->fresh();

        $tenant->load('domain');


        return TenantResource::make($tenant);
    }

    /**
     * Show the specified resource.
     * @return TenantResource
     */
    public function show(): TenantResource
    {
        $tenant = tenant()->fresh();

        $tenant->load('domain');

        return new TenantResource($tenant);
    }
}
