<?php

namespace App\Repositories\Payments;

use App\Models\Payment;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function findById(int $id): Payment
    {
        return Payment::findOrFail($id);
    }
}
