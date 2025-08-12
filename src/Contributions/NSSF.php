<?php

namespace Laque\Payroll\Contributions;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Domain\Employee;

/**
 * NSSF contribution calculator
 */
class NSSF implements ContributionInterface
{
    /**
     * @var float The employee contribution rate
     */
    private float $employeeRate = 0.10;
    
    /**
     * @var float The employer contribution rate
     */
    private float $employerRate = 0.10;
    
    /**
     * @var float The maximum contribution base
     */
    private float $maxBase = 85000;
    
    /**
     * Calculate the employee contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployeeContribution(Employee $employee): float
    {
        $base = min($employee->getBasicSalary(), $this->maxBase);
        return $base * $this->employeeRate;
    }
    
    /**
     * Calculate the employer contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployerContribution(Employee $employee): float
    {
        $base = min($employee->getBasicSalary(), $this->maxBase);
        return $base * $this->employerRate;
    }
    
    /**
     * Get the name of the contribution
     * 
     * @return string The name
     */
    public function getName(): string
    {
        return 'NSSF';
    }
}