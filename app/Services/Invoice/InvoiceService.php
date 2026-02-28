<?php

namespace App\Services;

use App\DTOs\CreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Payments\PaymentRepositoryInterface;
use App\Services\Tax\TaxService;
use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Payment;

class InvoiceService
{
    public function __construct(
        private ContractRepositoryInterface $contractRepo,
        private InvoiceRepositoryInterface $invoiceRepo,
        private PaymentRepositoryInterface $paymentRepo,
        private TaxService $taxService,
    ) {}

    public function createInvoice(CreateInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($dto) {

            $contract = $this->contractRepo->findById($dto->contract_id);

            if ($contract->status !== ContractStatus::Active) {
                throw new \DomainException('Contract is not active.');
            }

            $subtotal = $contract->rent_amount;
            $taxAmount = $this->taxService->calculateTotalTax($subtotal);
            $total = $subtotal + $taxAmount;

            $invoiceNumber = $this->generateInvoiceNumber($dto->tenant_id);

            return $this->invoiceRepo->create([
                'tenant_id' => $dto->tenant_id,
                'contract_id' => $contract->id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => InvoiceStatus::Pending,
                'due_date' => $dto->due_date,
            ]);
        });
    }

    public function recordPayment(RecordPaymentDTO $dto): Payment
    {
        return DB::transaction(function () use ($dto) {

            $invoice = $this->invoiceRepo->findById($dto->invoice_id);

            $paidSoFar = $invoice->payments()->sum('amount');
            $remaining = $invoice->total - $paidSoFar;

            if ($dto->amount > $remaining) {
                throw new \DomainException('Payment exceeds remaining balance.');
            }

            $payment = $this->paymentRepo->create([
                'tenant_id' => $invoice->tenant_id,
                'invoice_id' => $invoice->id,
                'amount' => $dto->amount,
                'payment_method' => $dto->payment_method,
                'reference_number' => $dto->reference_number,
                'paid_at' => now(),
            ]);

            $this->updateInvoiceStatus($invoice);

            return $payment;
        });
    }

    public function getContractSummary(int $contractId): array
    {
        $invoices = $this->invoiceRepo->getByContractId($contractId);

        $totalInvoiced = $invoices->sum('total');
        $totalPaid = $invoices->sum(fn($inv) => $inv->payments->sum('amount'));

        return [
            'contract_id' => $contractId,
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $totalInvoiced - $totalPaid,
            'invoices_count' => $invoices->count(),
            'latest_invoice_date' => $invoices->max('created_at'),
        ];
    }

    private function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments()->sum('amount');

        if ($totalPaid == $invoice->total) {
            $invoice->update([
                'status' => InvoiceStatus::Paid,
                'paid_at' => now(),
            ]);
        } elseif ($totalPaid > 0) {
            $invoice->update([
                'status' => InvoiceStatus::PartiallyPaid,
            ]);
        }
    }

    private function generateInvoiceNumber(int $tenantId): string
    {
        $sequence = str_pad(
            Invoice::where('tenant_id', $tenantId)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return sprintf(
            'INV-%03d-%s-%s',
            $tenantId,
            now()->format('Ym'),
            $sequence
        );
    }
}
