<?php

namespace App\DTOs;
use App\Models\Contract;

class CreateInvoiceDTO
{
    public function __construct(
        public readonly int $contract_id,
        public readonly string $due_date,
        public readonly int $tenant_id,
    ) {}

    public static function fromRequest($request, Contract $contract): self
    {
        return new self(
            contract_id: $contract->id,
            tenant_id: $contract->tenant_id,
            due_date: $request->validated('due_date'),
        );
    }
}
