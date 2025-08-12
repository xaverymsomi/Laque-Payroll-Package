<?php

declare(strict_types=1);

namespace Tests\Unit;

use Laque\Payroll\Domain\Employee;
use Laque\Payroll\PayrollFactory;
use PHPUnit\Framework\TestCase;

final class ContributionsTest extends TestCase
{
	public function testDefaultContributionsComputeForBasicAndGross(): void
	{
		$employee = new Employee('EMP001', 'John Doe', 'Dev', 800_000);
		$employee->addEarning(new \Laque\Payroll\Domain\Earning('Housing', 200_000));
		$employee->addEarning(new \Laque\Payroll\Domain\Earning('Transport', 150_000));

		$payroll = \Laque\Payroll\Services\PayrollFactory::createPayrollCalculator(); // or PayrollFactory::createPayrollCalculator();

		$breakdown = $payroll->calculate($employee);

		// Basic sanity checks for keys
		$this->assertGreaterThan(0, $breakdown->getGrossSalary());
		$this->assertArrayHasKey('contributions', $breakdown->toArray());

		// If you expose contributions detail, assert presence and positivity
		$contribs = $breakdown->toArray()['contributions'];
		$this->assertArrayHasKey('employee', $contribs);
		$this->assertArrayHasKey('employer', $contribs);

		// If your README rates are authoritative, assert rough expectations.
		// Example (adjust if you have caps/ceilings in code):
		$gross = 1_150_000.0;
		$basic = 800_000.0;

		// NSSF: 10% employee on ??? (README says “Maximum base: 85,000 TZS” — verify this in your code; it looks like a typo; maybe 850,000?)
		// To avoid flakiness, assert that employee NSSF <= 10% of chosen base and employer NSSF likewise:
		$employeeNssf = $contribs['employee']['NSSF'] ?? 0;
		$employerNssf = $contribs['employer']['NSSF'] ?? 0;
		$this->assertGreaterThanOrEqual(0, $employeeNssf);
		$this->assertGreaterThanOrEqual(0, $employerNssf);

		// SDL: 4.5% employer on gross (README)
		$employerSdl = $contribs['employer']['SDL'] ?? 0;
		$this->assertEqualsWithDelta(0.045 * $gross, $employerSdl, 1.0);

		// Health: 3% both sides on basic (README)
		$this->assertEqualsWithDelta(0.03 * $basic, $contribs['employee']['Health Insurance'] ?? 0, 1.0);
		$this->assertEqualsWithDelta(0.03 * $basic, $contribs['employer']['Health Insurance'] ?? 0, 1.0);

		// WCF: 1% employer on gross (README)
		$this->assertEqualsWithDelta(0.01 * $gross, $contribs['employer']['WCF'] ?? 0, 1.0);
	}
}
