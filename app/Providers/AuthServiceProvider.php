<?php

class AuthServiceProvider
{
    protected $policies = [
        \App\Models\Invoice::class => \App\Policies\InvoicePolicy::class,
    ];
}
