<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;

class MarkOverdueInvoices extends Command
{
    protected $signature = 'invoices:mark-overdue';
    protected $description = 'Mark pending invoices as overdue';

    public function handle(): int
    {
        Invoice::where('status', InvoiceStatus::Pending)
            ->whereDate('due_date', '<', today())
            ->update(['status' => InvoiceStatus::Overdue]);

        $this->info('Overdue invoices updated.');

        return Command::SUCCESS;
    }
}
