<?php

namespace Laque\Payroll\Renderers;

use Laque\Payroll\Contracts\PayslipRendererInterface;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;

/**
 * HTML-based payslip renderer
 */
class HtmlPayslipRenderer implements PayslipRendererInterface
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
        $html = [];
        $html[] = '<!DOCTYPE html>';
        $html[] = '<html lang="en">';
        $html[] = '<head>';
        $html[] = '  <meta charset="UTF-8">';
        $html[] = '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html[] = '  <title>Payslip - ' . htmlspecialchars($employee->getName()) . '</title>';
        $html[] = '  <style>';
        $html[] = '    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }';
        $html[] = '    .payslip { max-width: 800px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }';
        $html[] = '    .header { text-align: center; padding-bottom: 20px; border-bottom: 2px solid #333; }';
        $html[] = '    .employee-info { margin: 20px 0; }';
        $html[] = '    .employee-info table { width: 100%; }';
        $html[] = '    .section { margin: 20px 0; }';
        $html[] = '    .section h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }';
        $html[] = '    table { width: 100%; border-collapse: collapse; }';
        $html[] = '    table th, table td { padding: 8px; text-align: left; }';
        $html[] = '    .amount { text-align: right; }';
        $html[] = '    .total-row { font-weight: bold; border-top: 1px solid #333; }';
        $html[] = '    .final-row { font-weight: bold; font-size: 1.1em; background-color: #f8f8f8; }';
        $html[] = '  </style>';
        $html[] = '</head>';
        $html[] = '<body>';
        $html[] = '  <div class="payslip">';
        
        // Header
        $html[] = '    <div class="header">';
        $html[] = '      <h1>PAYSLIP</h1>';
        $html[] = '    </div>';
        
        // Employee information
        $html[] = '    <div class="employee-info">';
        $html[] = '      <h3>Employee Information</h3>';
        $html[] = '      <table>';
        $html[] = '        <tr><th>Name:</th><td>' . htmlspecialchars($employee->getName()) . '</td></tr>';
        $html[] = '        <tr><th>Employee ID:</th><td>' . htmlspecialchars($employee->getId()) . '</td></tr>';
        $html[] = '        <tr><th>Position:</th><td>' . htmlspecialchars($employee->getPosition()) . '</td></tr>';
        $html[] = '      </table>';
        $html[] = '    </div>';
        
        // Earnings
        $html[] = '    <div class="section">';
        $html[] = '      <h3>Earnings</h3>';
        $html[] = '      <table>';
        $html[] = '        <tr><th>Description</th><th class="amount">Amount (TZS)</th></tr>';
        $html[] = '        <tr><td>Basic Salary</td><td class="amount">' . number_format($employee->getBasicSalary(), 2) . '</td></tr>';
        
        foreach ($employee->getEarnings() as $earning) {
            $html[] = '        <tr><td>' . htmlspecialchars($earning->getName()) . '</td><td class="amount">' . number_format($earning->getAmount(), 2) . '</td></tr>';
        }
        
        $html[] = '        <tr class="total-row"><td>Gross Salary</td><td class="amount">' . number_format($breakdown->getTotalEarnings(), 2) . '</td></tr>';
        $html[] = '      </table>';
        $html[] = '    </div>';
        
        // Statutory deductions
        $html[] = '    <div class="section">';
        $html[] = '      <h3>Statutory Deductions</h3>';
        $html[] = '      <table>';
        $html[] = '        <tr><th>Description</th><th class="amount">Amount (TZS)</th></tr>';
        
        foreach ($breakdown->getStatutoryDeductions() as $deduction) {
            $html[] = '        <tr><td>' . htmlspecialchars($deduction->getName()) . '</td><td class="amount">' . number_format($deduction->getAmount(), 2) . '</td></tr>';
        }
        
        $html[] = '        <tr class="total-row"><td>Total Statutory Deductions</td><td class="amount">' . number_format($breakdown->getTotalStatutoryDeductions(), 2) . '</td></tr>';
        $html[] = '      </table>';
        $html[] = '    </div>';
        
        // Other deductions
        if (count($employee->getDeductions()) > 0) {
            $html[] = '    <div class="section">';
            $html[] = '      <h3>Other Deductions</h3>';
            $html[] = '      <table>';
            $html[] = '        <tr><th>Description</th><th class="amount">Amount (TZS)</th></tr>';
            
            foreach ($employee->getDeductions() as $deduction) {
                $html[] = '        <tr><td>' . htmlspecialchars($deduction->getName()) . '</td><td class="amount">' . number_format($deduction->getAmount(), 2) . '</td></tr>';
            }
            
            $html[] = '      </table>';
            $html[] = '    </div>';
        }
        
        // Summary
        $html[] = '    <div class="section">';
        $html[] = '      <h3>Summary</h3>';
        $html[] = '      <table>';
        $html[] = '        <tr><td>Total Deductions</td><td class="amount">' . number_format($breakdown->getTotalDeductions(), 2) . '</td></tr>';
        $html[] = '        <tr class="final-row"><td>NET PAY</td><td class="amount">' . number_format($breakdown->getNetPay(), 2) . '</td></tr>';
        $html[] = '      </table>';
        $html[] = '    </div>';
        
        $html[] = '  </div>';
        $html[] = '</body>';
        $html[] = '</html>';
        
        return implode("\n", $html);
    }
}