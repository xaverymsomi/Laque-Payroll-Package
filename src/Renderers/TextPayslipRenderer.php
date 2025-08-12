<?php

namespace Laque\Payroll\Renderers;

use Laque\Payroll\Contracts\PayslipRendererInterface;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;

/**
 * Text-based payslip renderer
 */
class TextPayslipRenderer implements PayslipRendererInterface
{
    /**
     * Render a payslip
     * 
     * @param Employee $employee The employee
     * @param PayrollBreakdown $breakdown The payroll breakdown
     * @return string The rendered payslip
     */
    public function render(Employee $employee, PayrollBreakdown $breakdown): string
    {
        $output = [];
        $output[] = str_repeat('=', 60);
        $output[] = str_pad("PAYSLIP", 60, ' ', STR_PAD_BOTH);
        $output[] = str_repeat('=', 60);
        $output[] = "";
        
        // Employee information
        $output[] = "EMPLOYEE INFORMATION:";
        $output[] = "Name: " . $employee->getFullName();
        $output[] = "Employee ID: " . $employee->getEmployeeId();
        $output[] = "Position: " . $employee->getPosition();
        $output[] = "";
        
        // Earnings
        $output[] = "EARNINGS:";
        $output[] = sprintf("%-30s %30s", "Basic Salary:", number_format($employee->getBasicSalary(), 2) . " TZS");
        
        foreach ($employee->getEarnings() as $earning) {
            $output[] = sprintf("%-30s %30s", $earning->getDescription() . ":", number_format($earning->getAmount(), 2) . " TZS");
        }
        
        $output[] = sprintf("%-30s %30s", "GROSS SALARY:", number_format($breakdown->getTotalEarnings(), 2) . " TZS");
        $output[] = str_repeat('-', 60);
        
        // Statutory deductions
        $output[] = "STATUTORY DEDUCTIONS:";
        foreach ($breakdown->getStatutoryDeductions() as $deduction) {
            $output[] = sprintf("%-30s %30s", $deduction->getDescription() . ":", number_format($deduction->getAmount(), 2) . " TZS");
        }
        
        $output[] = sprintf("%-30s %30s", "Total Statutory Deductions:", number_format($breakdown->getTotalStatutoryDeductions(), 2) . " TZS");
        $output[] = str_repeat('-', 60);
        
        // Other deductions
        if (count($employee->getDeductions()) > 0) {
            $output[] = "OTHER DEDUCTIONS:";
            foreach ($employee->getDeductions() as $deduction) {
                $output[] = sprintf("%-30s %30s", $deduction->getDescription() . ":", number_format($deduction->getAmount(), 2) . " TZS");
            }
            $output[] = str_repeat('-', 60);
        }
        
        // Summary
        $output[] = sprintf("%-30s %30s", "TOTAL DEDUCTIONS:", number_format($breakdown->getTotalDeductions(), 2) . " TZS");
        $output[] = str_repeat('=', 60);
        $output[] = sprintf("%-30s %30s", "NET PAY:", number_format($breakdown->getNetPay(), 2) . " TZS");
        $output[] = str_repeat('=', 60);
        
        return implode("\n", $output);
    }
}