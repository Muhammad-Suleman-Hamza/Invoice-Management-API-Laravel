<?php

class RecordPaymentDTO
{
    public function __construct(
        public readonly int $invoice_id,
        public readonly float $amount,
        public readonly string $payment_method,
        public readonly ?string $reference_number,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            invoice_id: $request->route('id'),
            amount: $request->validated('amount'),
            payment_method: $request->validated('payment_method'),
            reference_number: $request->validated('reference_number'),
        );
    }
}
