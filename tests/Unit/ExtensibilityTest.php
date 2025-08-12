<?php

declare(strict_types=1);

namespace Tests\Unit;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Contracts\PayslipRendererInterface;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;
use Laque\Payroll\PayrollFactory;
use PHPUnit\Framework\TestCase;

final class ExtensibilityTest extends TestCase
{
	public function testCustomContributionAndRendererCanBeInjected(): void
	{
		$employee = new Employee('E1', 'Alice', 'Engineer', 500_000);

		$tax = PayrollFactory::createTaxCalculator(2024);
		$calc = new \Laque\Payroll\Services\PayrollCalculator($tax);
		$calc->addContribution(new class implements ContributionInterface {
			public function getName(): string { return 'Custom Contribution'; }
			public function calculateEmployeeContribution(Employee $e): float { return 0.02 * $e->getBasicSalary(); }
			public function calculateEmployerContribution(Employee $e): float { return 0.03 * $e->getBasicSalary(); }
		});

		$breakdown = $calc->calculate($employee);

		$this->assertArrayHasKey('Custom Contribution', $breakdown->toArray()['contributions']['employee']);
		$this->assertArrayHasKey('Custom Contribution', $breakdown->toArray()['contributions']['employer']);

		$renderer = new class implements PayslipRendererInterface {
			public function render(Employee $e, PayrollBreakdown $b): string
			{
				return "PAYSLIP: {$e->getCode()} NET: {$b->getNetPay()}";
			}
		};

		$out = $renderer->render($employee, $breakdown);
		$this->assertStringContainsString('PAYSLIP:', $out);
		$this->assertStringContainsString('NET:', $out);
	}
}
