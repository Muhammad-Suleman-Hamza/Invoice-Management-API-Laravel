<?php

namespace App\Services\Tax\Calculators;
use App\Services\Tax\Interfaces\TaxCalculatorInterface;

class VATCalculator implements TaxCalculatorInterface
{
    public function calculate(float $amount): float
    {
        return $amount * 0.15;
    }
}
