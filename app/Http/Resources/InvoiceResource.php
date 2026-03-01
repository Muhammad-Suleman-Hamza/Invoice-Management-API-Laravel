<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paid = $this->payments->sum('amount');

        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'status' => $this->status->value,
            'due_date' => $this->due_date,
            'paid_at' => $this->paid_at,
            'remaining_balance' => $this->total - $paid,
            'contract' => $this->whenLoaded('contract'),
            'payments' => PaymentResource::collection(
                $this->whenLoaded('payments')
            ),
        ];
    }
}
