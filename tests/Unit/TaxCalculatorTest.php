<?php

declare(strict_types=1);

namespace Tests\Unit;

use Laque\Payroll\PayrollFactory;
use PHPUnit\Framework\TestCase;

final class TaxCalculatorTest extends TestCase
{
	/**
	 * 2024 bands per README:
	 * 0-270,000: 0%
	 * 270,000-520,000: 8%
	 * 520,000-760,000: 20%
	 * 760,000-1,000,000: 25%
	 * >1,000,000: 30%
	 */
	public static function cases2024(): array
	{
		return [
			'lower bound zero tax' => [0, 0],
			'just below 270k'     => [269_999, 0],
			'exact 270k'          => [270_000, 0.08 * (270_000 - 270_000)], // 0
			'middle 2nd band'     => [400_000, 0.08 * (400_000 - 270_000)],
			'exact 520k'          => [
				520_000,
				0.08 * (520_000 - 270_000)
			],
			'middle 3rd band'     => [
				600_000,
				0.08 * (520_000 - 270_000)
				+ 0.20 * (600_000 - 520_000)
			],
			'exact 760k'          => [
				760_000,
				0.08 * (520_000 - 270_000)
				+ 0.20 * (760_000 - 520_000)
			],
			'middle 4th band'     => [
				900_000,
				0.08 * (520_000 - 270_000)
				+ 0.20 * (760_000 - 520_000)
				+ 0.25 * (900_000 - 760_000)
			],
			'exact 1,000,000'     => [
				1_000_000,
				0.08 * (520_000 - 270_000)
				+ 0.20 * (760_000 - 520_000)
				+ 0.25 * (1_000_000 - 760_000)
			],
			'above 1,000,000'     => [
				1_150_000,
				0.08 * (520_000 - 270_000)
				+ 0.20 * (760_000 - 520_000)
				+ 0.25 * (1_000_000 - 760_000)
				+ 0.30 * (1_150_000 - 1_000_000)
			],
		];
	}

	/**
	 * @dataProvider cases2024
	 */
	public function testCalculatePaye2024(float $taxable, float $expected): void
	{
		$tax = PayrollFactory::createTaxCalculator(2024);
		$actual = $tax->calculate($taxable); // adjust if your method name differs
		$this->assertEqualsWithDelta($expected, $actual, 0.5, 'PAYE diff > 0.5 TZS');
	}
}
