<?php

namespace Laque\Payroll\Tax;

/**
 * Class for tax bands
 */
class TaxBand
{
    /**
     * @var float The lower limit
     */
    private float $lower;
    
    /**
     * @var float The upper limit
     */
    private float $upper;
    
    /**
     * @var float The rate
     */
    private float $rate;
    
    /**
     * Create a new tax band
     * 
     * @param float $lower The lower limit
     * @param float $upper The upper limit
     * @param float $rate The rate
     */
    public function __construct(float $lower, float $upper, float $rate)
    {
        $this->lower = $lower;
        $this->upper = $upper;
        $this->rate = $rate;
    }
    
    /**
     * Get the lower limit
     * 
     * @return float The lower limit
     */
    public function getLower(): float
    {
        return $this->lower;
    }
    
    /**
     * Get the upper limit
     * 
     * @return float The upper limit
     */
    public function getUpper(): float
    {
        return $this->upper;
    }
    
    /**
     * Get the rate
     * 
     * @return float The rate
     */
    public function getRate(): float
    {
        return $this->rate;
    }
    
    /**
     * Check if the income is within this band
     * 
     * @param float $income The income
     * @return bool True if the income is within this band
     */
    public function contains(float $income): bool
    {
        return $income > $this->lower && $income <= $this->upper;
    }
    
    /**
     * Calculate the tax for this band
     * 
     * @param float $income The income
     * @return float The tax
     */
    public function calculateTax(float $income): float
    {
        $taxableAmount = min($income, $this->upper) - $this->lower;
        return max(0, $taxableAmount) * $this->rate;
    }
}