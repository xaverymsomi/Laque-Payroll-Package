<?php

namespace Laque\Payroll\Contracts;

use Laque\Payroll\Domain\Employee;

/**
 * Interface for contributions
 */
interface ContributionInterface
{
    /**
     * Calculate the employee contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployeeContribution(Employee $employee): float;
    
    /**
     * Calculate the employer contribution
     * 
     * @param Employee $employee The employee
     * @return float The contribution amount
     */
    public function calculateEmployerContribution(Employee $employee): float;
    
    /**
     * Get the name of the contribution
     * 
     * @return string The name
     */
    public function getName(): string;
}