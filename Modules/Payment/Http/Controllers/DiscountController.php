<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Discount;
use Modules\Payment\Http\Requests\DiscountRequest;
use Modules\Payment\Repositories\DiscountRepo;
use Modules\Payment\Transformers\DiscountResource;

class DiscountController extends Controller
{
    private function discountRepo(): DiscountRepo
    {
        return new DiscountRepo();
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return DiscountResource::collection(
            $this->discountRepo()->discounts()
        );
    }

    /**
     * @param DiscountRequest $request
     * @return DiscountResource
     */
    public function store(DiscountRequest $request): DiscountResource
    {
        return new DiscountResource(
            $this->discountRepo()->create($request->validated())
        );
    }

    /**
     * @param Discount $discount
     * @return DiscountResource
     */
    public function show(Discount $discount): DiscountResource
    {
        return new DiscountResource($discount);
    }

    /**
     * @param DiscountRequest $request
     * @param Discount $discount
     * @return DiscountResource
     */
    public function update(DiscountRequest $request, Discount $discount): DiscountResource
    {
        return new DiscountResource(
            $this->discountRepo()->update($discount, $request->validated())
        );
    }

    /**
     * @param Discount $discount
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Discount $discount): \Illuminate\Http\JsonResponse
    {
        $this->discountRepo()->delete($discount);

        return response()->json([
            'message' => 'Discount deleted successfully'
        ]);
    }
}
