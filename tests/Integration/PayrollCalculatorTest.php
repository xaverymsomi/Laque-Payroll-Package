<?php

declare(strict_types=1);

namespace Tests\Integration;

use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\Domain\Earning;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\PayrollFactory;
use PHPUnit\Framework\TestCase;

final class PayrollCalculatorTest extends TestCase
{
	public function testEndToEndBreakdownAndPayslips(): void
	{
		$employee = new Employee('EMP001', 'John Doe', 'Senior Developer', 800_000);
		$employee->addEarning(new Earning('Housing Allowance', 200_000));
		$employee->addEarning(new Earning('Transport Allowance', 150_000));
		$employee->addDeduction(new Deduction('Loan Repayment', 50_000));

		$calculator = PayrollFactory::createPayrollCalculator(); // defaults for contributions + tax year
		$breakdown  = $calculator->calculate($employee);

		// Simple invariants:
		$this->assertSame(1_150_000.0, $breakdown->getGrossSalary());
		$this->assertGreaterThanOrEqual(0, $breakdown->getTaxableIncome());
		$this->assertGreaterThanOrEqual(0, $breakdown->getPaye());
		$this->assertGreaterThan(0, $breakdown->getNetPay());

		// Text payslip
		$text = PayrollFactory::createPayslipRenderer('text')->render($employee, $breakdown);
		$this->assertStringContainsString('EMP001', $text);
		$this->assertStringContainsString('John Doe', $text);
		$this->assertStringContainsString('Gross', $text);
		$this->assertStringContainsString('Net', $text);

		// HTML payslip
		$html = PayrollFactory::createPayslipRenderer('html')->render($employee, $breakdown);
		$this->assertStringContainsString('<html', $html);
		$this->assertStringContainsString('EMP001', $html);
		$this->assertStringContainsString('Gross', $html);
		$this->assertStringContainsString('Net', $html);
	}
}
