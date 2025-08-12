<?php

namespace Laque\Payroll\Domain;

/**
 * Data transfer object for payroll calculation results
 */
class PayrollBreakdown
{
    /**
     * @var float The total earnings
     */
    private float $totalEarnings;
    
    /**
     * @var array<Deduction> The statutory deductions
     */
    private array $statutoryDeductions;
    
    /**
     * @var float The total statutory deductions
     */
    private float $totalStatutoryDeductions;
    
    /**
     * @var float The total deductions
     */
    private float $totalDeductions;
    
    /**
     * @var float The net pay
     */
    private float $netPay;
    
    /**
     * @var float The total employer contributions
     */
    private float $totalEmployerContributions;
    
    /**
     * Create a new payroll breakdown
     * 
     * @param float $totalEarnings The total earnings
     * @param array<Deduction> $statutoryDeductions The statutory deductions
     * @param float $totalStatutoryDeductions The total statutory deductions
     * @param float $totalDeductions The total deductions
     * @param float $netPay The net pay
     * @param float $totalEmployerContributions The total employer contributions
     */
    public function __construct(
        float $totalEarnings,
        array $statutoryDeductions,
        float $totalStatutoryDeductions,
        float $totalDeductions,
        float $netPay,
        float $totalEmployerContributions
    ) {
        $this->totalEarnings = $totalEarnings;
        $this->statutoryDeductions = $statutoryDeductions;
        $this->totalStatutoryDeductions = $totalStatutoryDeductions;
        $this->totalDeductions = $totalDeductions;
        $this->netPay = $netPay;
        $this->totalEmployerContributions = $totalEmployerContributions;
    }
    
    /**
     * Get the total earnings
     * 
     * @return float The total earnings
     */
    public function getTotalEarnings(): float
    {
        return $this->totalEarnings;
    }
    
    /**
     * Get the statutory deductions
     * 
     * @return array<Deduction> The statutory deductions
     */
    public function getStatutoryDeductions(): array
    {
        return $this->statutoryDeductions;
    }
    
    /**
     * Get the total statutory deductions
     * 
     * @return float The total statutory deductions
     */
    public function getTotalStatutoryDeductions(): float
    {
        return $this->totalStatutoryDeductions;
    }
    
    /**
     * Get the total deductions
     * 
     * @return float The total deductions
     */
    public function getTotalDeductions(): float
    {
        return $this->totalDeductions;
    }
    
    /**
     * Get the net pay
     * 
     * @return float The net pay
     */
    public function getNetPay(): float
    {
        return $this->netPay;
    }
    
    /**
     * Get the total employer contributions
     * 
     * @return float The total employer contributions
     */
    public function getTotalEmployerContributions(): float
    {
        return $this->totalEmployerContributions;
    }
}