<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\RecordPaymentRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentResource;
use App\Models\Contract;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    public function store(StoreInvoiceRequest $request, Contract $contract)
    {
        $this->authorize('create', [Invoice::class, $contract]);

        $dto = CreateInvoiceDTO::fromRequest($request);

        $invoice = $this->invoiceService->createInvoice($dto);

        return InvoiceResource::make($invoice)
            ->response()
            ->setStatusCode(201);
    }

    public function recordPayment(RecordPaymentRequest $request, Invoice $invoice)
    {
        $this->authorize('recordPayment', $invoice);

        $dto = RecordPaymentDTO::fromRequest($request);

        $payment = $this->invoiceService->recordPayment($dto);

        return PaymentResource::make($payment)
            ->response()
            ->setStatusCode(201);
    }
}
