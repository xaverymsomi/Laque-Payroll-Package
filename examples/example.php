<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\Domain\Earning;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\PayrollFactory;

// Create an employee
$employee = new Employee(
    'EMP001',
    'John',
    'Doe',
    800000,
	// Basic salary
);
$employee->setPosition('Senior Developer');

// Add some additional earnings
$employee->addEarning(new Earning('Housing Allowance', 200000));
$employee->addEarning(new Earning('Transport Allowance', 150000));

// Add some personal deductions
$employee->addDeduction(new Deduction('Loan Repayment', 50000));
$employee->addDeduction(new Deduction('Advance', 30000));

// Create the payroll calculator with default contributions
$payrollCalculator = PayrollFactory::createPayrollCalculator();

// Calculate the payroll breakdown
$breakdown = $payrollCalculator->calculate($employee);

// Create a text payslip renderer and render the payslip
$textRenderer = PayrollFactory::createPayslipRenderer('text');
$textPayslip = $textRenderer->render($employee, $breakdown);

echo "TEXT PAYSLIP:\n";
echo $textPayslip;
echo "\n\n";

// Create an HTML payslip renderer and render the payslip
$htmlRenderer = PayrollFactory::createPayslipRenderer('html');
$htmlPayslip = $htmlRenderer->render($employee, $breakdown);

// Save the HTML payslip to a file
file_put_contents(__DIR__ . '/payslip.html', $htmlPayslip);
echo "HTML payslip saved to: " . __DIR__ . '/payslip.html' . "\n";

// Print out the breakdown details
echo "\nPAYROLL BREAKDOWN SUMMARY:\n";
echo "Gross Salary: " . number_format($breakdown->getTotalEarnings(), 2) . " TZS\n";
echo "Total Statutory Deductions: " . number_format($breakdown->getTotalStatutoryDeductions(), 2) . " TZS\n";
echo "Total Deductions: " . number_format($breakdown->getTotalDeductions(), 2) . " TZS\n";
echo "Net Pay: " . number_format($breakdown->getNetPay(), 2) . " TZS\n";
echo "Employer Contributions: " . number_format($breakdown->getTotalEmployerContributions(), 2) . " TZS\n";