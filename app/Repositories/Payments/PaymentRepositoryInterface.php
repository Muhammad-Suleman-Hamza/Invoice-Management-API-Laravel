<?php

namespace App\Repositories\Payments;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function findById(int $id): Payment;
}
