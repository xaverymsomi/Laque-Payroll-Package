# LaquePayroll

A PHP package for Tanzanian payroll calculations.

## Features

- PAYE (Pay As You Earn) tax calculation based on Tanzanian tax bands
- Statutory deductions: NSSF, SDL, Health Insurance, WCF
- Custom earnings and deductions
- Payslip generation in both text and HTML formats
- Flexible and extensible architecture

## Installation

```bash
composer require vicent/laque-payroll
```

## Usage

### Basic Usage

```php
<?php

require_once 'vendor/autoload.php';

use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\Earning;
use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\PayrollFactory;

// Create an employee
$employee = new Employee(
    'EMP001',
    'John','Doe',
    'Senior Developer',
    800000 // Basic salary
);

// Add some additional earnings
$employee->addEarning(new Earning('Housing Allowance', 200000));
$employee->addEarning(new Earning('Transport Allowance', 150000));

// Add some personal deductions
$employee->addDeduction(new Deduction('Loan Repayment', 50000));

// Create the payroll calculator with default contributions
$payrollCalculator = PayrollFactory::createPayrollCalculator();

// Calculate the payroll breakdown
$breakdown = $payrollCalculator->calculate($employee);

// Create a text payslip renderer and render the payslip
$textRenderer = PayrollFactory::createPayslipRenderer('text');
$textPayslip = $textRenderer->render($employee, $breakdown);

echo $textPayslip;
```

### Custom Payroll Configuration

```php
<?php

use Laque\Payroll\PayrollFactory;
use Laque\Payroll\Services\PayrollCalculator;

// Create tax calculator for a specific year
$taxCalculator = PayrollFactory::createTaxCalculator(2024);

// Create payroll calculator with custom configuration
$payrollCalculator = new PayrollCalculator($taxCalculator);

// Add only specific contributions
$payrollCalculator->addContribution(PayrollFactory::createContribution('NSSF'))
                 ->addContribution(PayrollFactory::createContribution('SDL'));
```

### Generate HTML Payslip

```php
<?php

use Laque\Payroll\PayrollFactory;

// Create an HTML payslip renderer
$htmlRenderer = PayrollFactory::createPayslipRenderer('html');
$htmlPayslip = $htmlRenderer->render($employee, $breakdown);

// Save to file
file_put_contents('payslip.html', $htmlPayslip);
```

## Tax Bands

The package includes tax bands for the years 2024 and 2025. The tax bands are used to calculate PAYE based on the taxable income.

### 2024 Tax Bands

| Income Range (TZS) | Rate |
|--------------------|------|
| 0 - 270,000        | 0%   |
| 270,000 - 520,000  | 8%   |
| 520,000 - 760,000  | 20%  |
| 760,000 - 1,000,000| 25%  |
| Above 1,000,000    | 30%  |

## Statutory Contributions

The package includes the following statutory contributions:

| Contribution      | Employee Rate | Employer Rate | Notes                         |
|-------------------|--------------|--------------|-------------------------------|
| NSSF              | 10%          | 10%          | Maximum base: 85,000 TZS       |
| SDL               | 0%           | 4.5%         | Based on gross salary         |
| Health Insurance  | 3%           | 3%           | Based on basic salary         |
| WCF               | 0%           | 1%           | Based on gross salary         |

## Extending the Package

### Creating Custom Contributions

```php
<?php

use Laque\Payroll\Contracts\ContributionInterface;
use Laque\Payroll\Domain\Employee;

class CustomContribution implements ContributionInterface
{
    public function calculateEmployeeContribution(Employee $employee): float
    {
        // Your custom calculation logic here
        return $employee->getBasicSalary() * 0.02;
    }
    
    public function calculateEmployerContribution(Employee $employee): float
    {
        // Your custom calculation logic here
        return $employee->getBasicSalary() * 0.03;
    }
    
    public function getName(): string
    {
        return 'Custom Contribution';
    }
}

// Use it with the payroll calculator
$payrollCalculator->addContribution(new CustomContribution());
```

### Creating Custom Payslip Renderers

```php
<?php

use Laque\Payroll\Contracts\PayslipRendererInterface;
use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\PayrollBreakdown;

class PdfPayslipRenderer implements PayslipRendererInterface
{
    public function render(Employee $employee, PayrollBreakdown $breakdown): string
    {
        // Your PDF generation logic here
        // ...
        
        return $pdfContent;
    }
}

// Use it to render payslips
$pdfRenderer = new PdfPayslipRenderer();
$pdfPayslip = $pdfRenderer->render($employee, $breakdown);
```

## Running Tests

```bash
composer test
```

## License

MIT