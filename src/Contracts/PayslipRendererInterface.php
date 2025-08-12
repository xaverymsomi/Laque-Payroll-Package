<?php

namespace Laque\Payroll\Contracts;

use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;

/**
 * Interface for rendering payslips
 */
interface PayslipRendererInterface
{
    /**
     * Render a payslip
     * 
     * @param Employee $employee The employee
     * @param PayrollBreakdown $breakdown The payroll breakdown
     * @return string The rendered payslip
     */
    public function render(Employee $employee, PayrollBreakdown $breakdown): string;
}