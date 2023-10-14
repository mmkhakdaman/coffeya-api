<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Address;
use Modules\Customer\Http\Requests\AddressRequest;
use Modules\Customer\Repositories\AddressRepository;
use Modules\Customer\Transformers\AddressResource;

class AddressController extends Controller
{
    private function repo(): AddressRepository
    {
        return resolve(AddressRepository::class);
    }


    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return AddressResource::collection(
            $this->repo()->getCustomerAddresses(auth()->id())
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function store(AddressRequest $request)
    {
        $address = $this->repo()->storeAddress(
            $request->name,
            $request->address,
            auth()->id()
        );

        return response()->json(
            [
                'message' => 'Address created successfully',
                'data' => new AddressResource($address),
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Show the specified resource.
     * @param Address $address
     * @return AddressResource
     */
    public function show(Address $address): AddressResource
    {
        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     * @param AddressRequest $request
     * @param Address $address
     * @return JsonResponse
     */
    public function update(AddressRequest $request, Address $address): JsonResponse
    {
        $this->repo()->updateAddress(
            $address,
            [
                'name' => $request->name,
                'address' => $request->address,
            ]
        );

        return response()->json(
            [
                'message' => 'Address updated successfully',
                'data' => new AddressResource($address),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Address $address
     * @return JsonResponse
     */
    public function destroy(Address $address): JsonResponse
    {
        $this->repo()->delete($address);

        return response()->json(
            [
                'message' => 'Address deleted successfully',
            ],
            Response::HTTP_OK
        );

    }
}
