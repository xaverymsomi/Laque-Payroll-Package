<?php

namespace Laque\Payroll\Domain;

/**
 * Data transfer object for deductions
 */
class Deduction
{
    /**
     * @var string The description
     */
    private string $description;
    
    /**
     * @var float The amount
     */
    private float $amount;
    
    /**
     * Create a new deduction
     * 
     * @param string $description The description
     * @param float $amount The amount
     */
    public function __construct(string $description, float $amount)
    {
        $this->description = $description;
        $this->amount = $amount;
    }
    
    /**
     * Get the description
     * 
     * @return string The description
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Get the amount
     * 
     * @return float The amount
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}