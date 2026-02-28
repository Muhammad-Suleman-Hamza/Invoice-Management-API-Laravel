<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(int $id): Invoice
    {
        return Invoice::findOrFail($id);
    }
}
