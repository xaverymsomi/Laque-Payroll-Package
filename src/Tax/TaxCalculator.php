<?php

namespace Laque\Payroll\Tax;

use Laque\Payroll\Contracts\TaxBandProviderInterface;

/**
 * Class for computing tax
 */
class TaxCalculator
{
    /**
     * @var TaxBandProviderInterface The tax band provider
     */
    private TaxBandProviderInterface $taxBandProvider;
    
    /**
     * Create a new tax calculator
     * 
     * @param TaxBandProviderInterface $taxBandProvider The tax band provider
     */
    public function __construct(TaxBandProviderInterface $taxBandProvider)
    {
        $this->taxBandProvider = $taxBandProvider;
    }
    
    /**
     * Calculate the tax
     * 
     * @param float $income The income
     * @return float The tax
     */
    public function calculate(float $income): float
    {
        $taxBands = $this->taxBandProvider->getTaxBands();
        $totalTax = 0;
        
        foreach ($taxBands as $band) {
            if ($income > $band->getLower()) {
                $totalTax += $band->calculateTax($income);
            }
        }
        
        return $totalTax;
    }
    
    /**
     * Get the tax band provider
     * 
     * @return TaxBandProviderInterface The tax band provider
     */
    public function getTaxBandProvider(): TaxBandProviderInterface
    {
        return $this->taxBandProvider;
    }
}