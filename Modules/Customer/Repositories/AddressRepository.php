<?php

namespace Modules\Customer\Repositories;


use Modules\Customer\Entities\Address;

class AddressRepository
{
    private function query()
    {
        return Address::query();
    }

    public function storeAddress(
        $name,
        $address,
        $customer_id
    ): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return $this->query()->create(
            [
                'name' => $name,
                'address' => $address,
                'customer_id' => $customer_id,
            ]
        );
    }

    public function updateAddress(Address $address, array $data): bool
    {
        return $address->update($data);
    }

    public function delete(Address $address): ?bool
    {
        return $address->delete();
    }

    public function getCustomerAddresses($customer_id): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->query()
            ->where('customer_id', $customer_id)
            ->latest()
            ->get();
    }
}
