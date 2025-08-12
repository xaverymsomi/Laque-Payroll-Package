<?php

namespace Laque\Payroll;

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Contracts\PayrollCalculatorInterface;
use Laque\Payroll\Contracts\PayslipRendererInterface;
use Laque\Payroll\Contracts\TaxBandProviderInterface;
use Laque\Payroll\Contributions\HealthInsurance;
use Laque\Payroll\Contributions\NSSF;
use Laque\Payroll\Contributions\SDL;
use Laque\Payroll\Contributions\WCF;
use Laque\Payroll\Renderers\HtmlPayslipRenderer;
use Laque\Payroll\Renderers\TextPayslipRenderer;
use Laque\Payroll\Services\PayrollCalculator;
use Laque\Payroll\Tax\Bands\Year2024;
use Laque\Payroll\Tax\Bands\Year2025;
use Laque\Payroll\Tax\TaxCalculator;

/**
 * Factory class for payroll components
 */
class PayrollFactory
{
    /**
     * Create a tax band provider based on the tax year
     * 
     * @param int|null $year The tax year, defaults to current year
     * @return TaxBandProviderInterface The tax band provider
     */
    public static function createTaxBandProvider(?int $year = null): TaxBandProviderInterface
    {
        $year = $year ?? date('Y');
        
        return match ($year) {
            2024 => new Year2024(),
            2025 => new Year2025(),
            default => new Year2024(), // Default to most recent year if not found
        };
    }
    
    /**
     * Create a tax calculator
     * 
     * @param int|null $year The tax year
     * @return TaxCalculator The tax calculator
     */
    public static function createTaxCalculator(?int $year = null): TaxCalculator
    {
        $bandProvider = self::createTaxBandProvider($year);
        return new TaxCalculator($bandProvider);
    }
    
    /**
     * Create a payroll calculator with all statutory contributions
     * 
     * @param int|null $year The tax year
     * @return PayrollCalculatorInterface The payroll calculator
     */
    public static function createPayrollCalculator(?int $year = null): PayrollCalculatorInterface
    {
        $taxCalculator = self::createTaxCalculator($year);
        
        $calculator = new PayrollCalculator($taxCalculator);
        
        // Add standard contributions
        $calculator->addContribution(new NSSF())
                   ->addContribution(new SDL())
                   ->addContribution(new HealthInsurance())
                   ->addContribution(new WCF());
        
        return $calculator;
    }
    
    /**
     * Create a contribution by name
     * 
     * @param string $name The contribution name
     * @return ContributionInterface|null The contribution or null if not found
     */
    public static function createContribution(string $name): ?ContributionInterface
    {
        return match (strtoupper($name)) {
            'NSSF' => new NSSF(),
            'SDL' => new SDL(),
            'HEALTH', 'HEALTHINSURANCE', 'HEALTH INSURANCE' => new HealthInsurance(),
            'WCF' => new WCF(),
            default => null,
        };
    }
    
    /**
     * Create a payslip renderer
     * 
     * @param string $format The format (html or text)
     * @return PayslipRendererInterface The payslip renderer
     */
    public static function createPayslipRenderer(string $format = 'text'): PayslipRendererInterface
    {
        return match (strtolower($format)) {
            'html' => new HtmlPayslipRenderer(),
            default => new TextPayslipRenderer(),
        };
    }
}