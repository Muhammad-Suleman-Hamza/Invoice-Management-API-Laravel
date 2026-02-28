<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function findById(int $id): Invoice;
}
