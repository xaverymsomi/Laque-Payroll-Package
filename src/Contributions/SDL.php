<?php

namespace Laque\Payroll\Contributions;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Domain\Employee;

/**
 * SDL contribution calculator
 */
class SDL implements ContributionInterface
{
    /**
     * @var float The employer contribution rate
     */
    private float $employerRate = 0.045;
    
    /**
     * Calculate the employee contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployeeContribution(Employee $employee): float
    {
        // SDL is only paid by employer
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
        // SDL is 4.5% of gross salary
        return $employee->getGrossSalary() * $this->employerRate;
    }
    
    /**
     * Get the name of the contribution
     * 
     * @return string The name
     */
    public function getName(): string
    {
        return 'SDL';
    }
}