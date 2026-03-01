<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(int $id): Invoice
    {
        return Invoice::findOrFail($id);
    }

    /**
     * Create a new invoice with the given data.
     * @param array $data
     * @return Invoice
     */
    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function getByContractId(int $contractId)
    {
        return Invoice::where('contract_id', $contractId)->get();
    }
}
