<?php

namespace Modules\Tenant\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Transformers\TenantResource;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $tenants = Tenant::query()->where('name', 'like', "%{$request->name}%")->get();


        return TenantResource::collection($tenants);
    }
}
