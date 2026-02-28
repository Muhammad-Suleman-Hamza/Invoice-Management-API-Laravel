<?php

class TaxService
{
    public function __construct(
        private iterable $calculators
    ) {}

    public function calculateTotalTax(float $amount): float
    {
        $total = 0;

        foreach ($this->calculators as $calculator) {
            $total += $calculator->calculate($amount);
        }

        return $total;
    }
}
