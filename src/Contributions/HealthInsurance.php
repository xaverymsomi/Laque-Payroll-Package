<?php

namespace Laque\Payroll\Contributions;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Domain\Employee;

/**
 * Health Insurance contribution calculator
 */
class HealthInsurance implements ContributionInterface
{
    /**
     * @var float The employee contribution rate
     */
    private float $employeeRate = 0.03;
    
    /**
     * @var float The employer contribution rate
     */
    private float $employerRate = 0.03;
    
    /**
     * Calculate the employee contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployeeContribution(Employee $employee): float
    {
        return $employee->getBasicSalary() * $this->employeeRate;
    }
    
    /**
     * Calculate the employer contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployerContribution(Employee $employee): float
    {
        return $employee->getBasicSalary() * $this->employerRate;
    }
    
    /**
     * Get the name of the contribution
     * 
     * @return string The name
     */
    public function getName(): string
    {
        return 'Health Insurance';
    }
}