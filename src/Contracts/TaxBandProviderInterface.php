<?php

namespace Laque\Payroll\Contracts;

use Laque\Payroll\Tax\TaxBand;

/**
 * Interface for providing tax bands
 */
interface TaxBandProviderInterface
{
    /**
     * Get the tax bands
     * 
     * @return array<TaxBand> The tax bands
     */
    public function getTaxBands(): array;
    
    /**
     * Get the tax year
     * 
     * @return int The tax year
     */
    public function getYear(): int;
}