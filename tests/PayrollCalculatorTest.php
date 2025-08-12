<?php

namespace Laque\Payroll\Tests;

use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\Domain\Earning;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\PayrollFactory;
use Laque\Payroll\Services\PayrollCalculator;
use Laque\Payroll\Tax\Bands\Year2024;
use Laque\Payroll\Tax\TaxCalculator;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for payroll calculations
 */
class PayrollCalculatorTest extends TestCase
{
    /**
     * Test basic PAYE calculation
     */
    public function testPayeCalculation(): void
    {
        $taxBandProvider = new Year2024();
        $taxCalculator = new TaxCalculator($taxBandProvider);
        
        // Test tax bands
        $this->assertEquals(0, $taxCalculator->calculate(250000)); // Below first tax band
        $this->assertEquals(10000, $taxCalculator->calculate(400000)); // In second tax band
        $this->assertEquals(57000, $taxCalculator->calculate(600000)); // In third tax band
        $this->assertEquals(117000, $taxCalculator->calculate(880000)); // In fourth tax band
        $this->assertEquals(177000, $taxCalculator->calculate(1100000)); // In fifth tax band
    }
    
    /**
     * Test payroll calculation with basic salary only
     */
    public function testPayrollCalculationBasicSalary(): void
    {
        $employee = new Employee('EMP001', 'John Doe', 'Developer', 500000);
        
        $taxCalculator = new TaxCalculator(new Year2024());
        $payrollCalculator = new PayrollCalculator($taxCalculator);
        
        // Add NSSF contribution only for simplicity in this test
        $nssf = PayrollFactory::createContribution('NSSF');
        $payrollCalculator->addContribution($nssf);
        
        $breakdown = $payrollCalculator->calculate($employee);
        
        // NSSF is 10% of basic salary
        $nssfAmount = 50000; // 500000 * 0.1
        
        // Taxable income is gross salary (just basic in this case) minus NSSF
        $taxableIncome = 500000 - $nssfAmount;
        
        // Calculate expected PAYE based on 2024 tax bands
        $expectedPaye = $taxCalculator->calculate($taxableIncome);
        
        // Total deductions should be NSSF + PAYE
        $expectedTotalDeductions = $nssfAmount + $expectedPaye;
        
        // Net pay should be gross salary minus total deductions
        $expectedNetPay = 500000 - $expectedTotalDeductions;
        
        $this->assertEquals(500000, $breakdown->getTotalEarnings());
        $this->assertEquals($expectedTotalDeductions, $breakdown->getTotalDeductions());
        $this->assertEquals($expectedNetPay, $breakdown->getNetPay());
        
        // Verify employer contributions (NSSF = 10% from employer too)
        $this->assertEquals($nssfAmount, $breakdown->getTotalEmployerContributions());
    }
    
    /**
     * Test payroll calculation with additional earnings and deductions
     */
    public function testPayrollCalculationWithEarningsAndDeductions(): void
    {
        $employee = new Employee('EMP002', 'Jane Doe', 'Manager', 800000);
        
        // Add additional earnings
        $employee->addEarning(new Earning('Housing Allowance', 200000));
        $employee->addEarning(new Earning('Transport Allowance', 100000));
        
        // Add personal deductions
        $employee->addDeduction(new Deduction('Loan Repayment', 50000));
        
        // Create payroll calculator with all standard contributions
        $payrollCalculator = PayrollFactory::createPayrollCalculator();
        
        $breakdown = $payrollCalculator->calculate($employee);
        
        // Verify gross salary is correctly calculated
        $expectedGross = 800000 + 200000 + 100000; // Basic + housing + transport
        $this->assertEquals($expectedGross, $breakdown->getTotalEarnings());
        
        // Verify total deductions include both statutory and personal
        $this->assertEquals(50000, $employee->getTotalDeductions());
        $this->assertTrue($breakdown->getTotalDeductions() > 50000); // Should include statutory + personal
        
        // Net pay should be gross salary minus all deductions
        $expectedNetPay = $expectedGross - $breakdown->getTotalDeductions();
        $this->assertEquals($expectedNetPay, $breakdown->getNetPay());
    }
}