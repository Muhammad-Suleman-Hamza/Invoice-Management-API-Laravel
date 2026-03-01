<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Services\Invoice\InvoiceService;
use App\Http\Controllers\Controller;
use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\ContractSummaryResource;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\RecordPaymentRequest;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    public function list(Contract $contract)
    {
        $invoices = $this->invoiceService->getInvoicesByContract($contract->id);

        return InvoiceResource::collection($invoices);
    }

    public function store(StoreInvoiceRequest $request, Contract $contract)
    {
        $this->authorize('create', [Invoice::class, $contract]);

        $dto = CreateInvoiceDTO::fromRequest($request, $contract);

        $invoice = $this->invoiceService->createInvoice($dto);

        return InvoiceResource::make($invoice)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return InvoiceResource::make(
            $invoice->load(['contract', 'payments'])
        );
    }

    public function recordPayment(
        RecordPaymentRequest $request,
        Invoice $invoice
    ) {
        $this->authorize('recordPayment', $invoice);

        $dto = RecordPaymentDTO::fromRequest($request);

        $payment = $this->invoiceService->recordPayment($dto);

        return PaymentResource::make($payment)
            ->response()
            ->setStatusCode(201);
    }

    public function contractSummary(Contract $contract)
    {
        $summary = $this->invoiceService->getContractSummary($contract->id);

        return new ContractSummaryResource($summary);
    }
}
