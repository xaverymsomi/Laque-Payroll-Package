<?php

namespace Laque\Payroll\Contributions;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Domain\Employee;

/**
 * WCF contribution calculator
 */
class WCF implements ContributionInterface
{
    /**
     * @var float The employer contribution rate
     */
    private float $employerRate = 0.01;
    
    /**
     * Calculate the employee contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployeeContribution(Employee $employee): float
    {
        // WCF is only paid by employer
        return 0;
    }
    
    /**
     * Calculate the employer contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployerContribution(Employee $employee): float
    {
        // WCF is 1% of gross salary
        return $employee->getGrossSalary() * $this->employerRate;
    }
    
    /**
     * Get the name of the contribution
     * 
     * @return string The name
     */
    public function getName(): string
    {
        return 'WCF';
    }
}