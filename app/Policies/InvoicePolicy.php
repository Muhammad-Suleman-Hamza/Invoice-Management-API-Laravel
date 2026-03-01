<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Contract;
use App\Enums\InvoiceStatus;

class InvoicePolicy
{
    /**
     * Determine if the user can create an invoice for the contract.
     */
    public function create(User $user, Contract $contract): bool
    {
        return $contract->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return $invoice->contract->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can record a payment on the invoice.
     * Cancelled invoices cannot receive payments.
     */
    public function recordPayment(User $user, Invoice $invoice): bool
    {
        $belongsToTenant = $invoice->contract->tenant_id === $user->tenant_id;
        $notCancelled = $invoice->status !== \App\Enums\InvoiceStatus::Cancelled;
        return $belongsToTenant && $notCancelled;
    }
}
