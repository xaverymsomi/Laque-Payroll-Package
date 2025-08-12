<?php

namespace Laque\Payroll\Contracts;

use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;

/**
 * Interface for payroll calculations
 */
interface PayrollCalculatorInterface
{
    /**
     * Add a contribution
     * 
     * @param ContributionInterface $contribution The contribution
     * @return self
     */
    public function addContribution(ContributionInterface $contribution): self;
    
    /**
     * Calculate the payroll breakdown for an employee
     * 
     * @param Employee $employee The employee
     * @return PayrollBreakdown The payroll breakdown
     */
    public function calculate(Employee $employee): PayrollBreakdown;
}