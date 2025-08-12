<?php

namespace Laque\Payroll\Services;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Contracts\PayrollCalculatorInterface;
use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;
use Laque\Payroll\Tax\TaxCalculator;

/**
 * Core PayrollCalculator service
 */
class PayrollCalculator implements PayrollCalculatorInterface
{
    /**
     * @var TaxCalculator The tax calculator
     */
    private TaxCalculator $taxCalculator;
    
    /**
     * @var array<ContributionInterface> The contributions
     */
    private array $contributions = [];
    
    /**
     * Create a new payroll calculator
     * 
     * @param TaxCalculator $taxCalculator The tax calculator
     */
    public function __construct(TaxCalculator $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }
    
    /**
     * Add a contribution
     * 
     * @param ContributionInterface $contribution The contribution
     * @return self
     */
    public function addContribution(ContributionInterface $contribution): self
    {
        $this->contributions[] = $contribution;
        return $this;
    }
    
    /**
     * Calculate the payroll breakdown for an employee
     * 
     * @param Employee $employee The employee
     * @return PayrollBreakdown The payroll breakdown
     */
    public function calculate(Employee $employee): PayrollBreakdown
    {
        // Calculate gross salary
        $totalEarnings = $employee->getGrossSalary();
        
        // Calculate statutory deductions
        $statutoryDeductions = [];
        $totalStatutoryDeductions = 0;
        $totalEmployerContributions = 0;
        
        // Calculate contribution deductions
        foreach ($this->contributions as $contribution) {
            $employeeContribution = $contribution->calculateEmployeeContribution($employee);
            $employerContribution = $contribution->calculateEmployerContribution($employee);
            
            if ($employeeContribution > 0) {
                $deduction = new Deduction(
                    $contribution->getName(),
                    $employeeContribution
                );
                $statutoryDeductions[] = $deduction;
                $totalStatutoryDeductions += $employeeContribution;
            }
            
            $totalEmployerContributions += $employerContribution;
        }
        
        // Calculate taxable income
        $taxableIncome = $totalEarnings - $totalStatutoryDeductions;
        
        // Calculate PAYE (tax)
        $payeTax = $this->taxCalculator->calculate($taxableIncome);
        
        if ($payeTax > 0) {
            $payeDeduction = new Deduction('PAYE', $payeTax);
            $statutoryDeductions[] = $payeDeduction;
            $totalStatutoryDeductions += $payeTax;
        }
        
        // Calculate other deductions
        $otherDeductions = $employee->getDeductions();
        $totalOtherDeductions = array_sum(array_map(function ($deduction) {
            return $deduction->getAmount();
        }, $otherDeductions));
        
        // Calculate total deductions
        $totalDeductions = $totalStatutoryDeductions + $totalOtherDeductions;
        
        // Calculate net pay
        $netPay = $totalEarnings - $totalDeductions;
        
        return new PayrollBreakdown(
            $totalEarnings,
            $statutoryDeductions,
            $totalStatutoryDeductions,
            $totalDeductions,
            $netPay,
            $totalEmployerContributions
        );
    }
}