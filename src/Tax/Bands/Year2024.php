<?php

namespace Laque\Payroll\Tax\Bands;

use Laque\Payroll\Contracts\TaxBandProviderInterface;
use Laque\Payroll\Tax\TaxBand;

/**
 * Tax bands for 2024
 */
class Year2024 implements TaxBandProviderInterface
{
    /**
     * Get the tax bands
     * 
     * @return array<TaxBand> The tax bands
     */
    public function getTaxBands(): array
    {
        return [
            new TaxBand(0, 270000, 0.00),
            new TaxBand(270000, 520000, 0.08),
            new TaxBand(520000, 760000, 0.20),
            new TaxBand(760000, 1000000, 0.25),
            new TaxBand(1000000, PHP_FLOAT_MAX, 0.30),
        ];
    }
    
    /**
     * Get the tax year
     * 
     * @return int The tax year
     */
    public function getYear(): int
    {
        return 2024;
    }
}