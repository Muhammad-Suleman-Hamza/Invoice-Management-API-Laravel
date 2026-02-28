<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\EloquentContractRepository;

use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Invoices\EloquentInvoiceRepository;

use App\Repositories\Payments\PaymentRepositoryInterface;
use App\Repositories\Payments\EloquentPaymentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ContractRepositoryInterface::class,
            EloquentContractRepository::class
        );

        $this->app->bind(
            InvoiceRepositoryInterface::class,
            EloquentInvoiceRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            EloquentPaymentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
