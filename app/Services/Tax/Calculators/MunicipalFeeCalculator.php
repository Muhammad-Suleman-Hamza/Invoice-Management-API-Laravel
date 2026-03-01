<?php

namespace App\Services\Tax\Calculators;
use App\Services\Tax\Interfaces\TaxCalculatorInterface;

class MunicipalFeeCalculator implements TaxCalculatorInterface
{
    public function calculate(float $amount): float
    {
        return $amount * 0.025;
    }
}
