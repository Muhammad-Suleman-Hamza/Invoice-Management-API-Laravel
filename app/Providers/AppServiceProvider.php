<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Contracts\EloquentContractRepository;

use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Invoices\EloquentInvoiceRepository;

use App\Repositories\Payments\PaymentRepositoryInterface;
use App\Repositories\Payments\EloquentPaymentRepository;

use App\Services\Tax\TaxService;
use App\Services\Tax\Calculators\VATCalculator;
use App\Services\Tax\Interfaces\TaxCalculatorInterface;
use App\Services\Tax\Calculators\MunicipalFeeCalculator;

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

        // Bind individual calculators
        $this->app->bind(VATCalculator::class);
        $this->app->bind(MunicipalFeeCalculator::class);

        // Tag them
        $this->app->tag([
            VATCalculator::class,
            MunicipalFeeCalculator::class,
        ], 'tax.calculators');

        // Bind TaxService with tagged calculators
        $this->app->bind(TaxService::class, function ($app) {
            return new TaxService(
                $app->tagged('tax.calculators')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
