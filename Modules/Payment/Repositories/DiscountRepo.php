<?php

namespace Modules\Payment\Repositories;

use Modules\Payment\Entities\Payment;

class DiscountRepo
{

    public function discounts(): \Illuminate\Database\Eloquent\Collection
    {
        return Payment::all();
    }

    public function create(mixed $validated)
    {
        return Payment::create($validated);
    }

    public function update(\Modules\Payment\Entities\Discount $discount, mixed $validated)
    {
        $discount->update($validated);
        return $discount;
    }

    public function delete(\Modules\Payment\Entities\Discount $discount): ?bool
    {
        return $discount->delete();
    }
}
