<?php

interface TaxCalculatorInterface
{
    public function calculate(float $amount): float;
}
