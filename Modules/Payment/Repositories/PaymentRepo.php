<?php

namespace Modules\Payment\Repositories;

use Modules\Payment\Models\Payment;

class PaymentRepo
{
    /**
     * Find payment by invoice id.
     *
     * @param string|int $invoiceId
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findByInvoiceId(string|int $invoiceId)
    {
        return Payment::query()->where('invoice_id', $invoiceId)->firstOrFail();
    }

    
    /**
     * Find payment by id.
     *
     * @param string|int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findById(string|int $id){
        return Payment::find($id);
    }
}
