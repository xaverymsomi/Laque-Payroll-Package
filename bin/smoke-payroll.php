#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Laque\Payroll\Domain\Employee;
use Laque\Payroll\Domain\Earning;
use Laque\Payroll\Domain\Deduction;
use Laque\Payroll\PayrollFactory;

$e = new Employee(
        'EMP001',
        'John',
        'Doe',
        800_000
	);
$e->setPosition('Senior Developer');

$e->addEarning(new Earning('Housing Allowance', 200_000));
$e->addEarning(new Earning('Transport Allowance', 150_000));
$e->addDeduction(new Deduction('Loan Repayment', 50_000));

$calc = PayrollFactory::createPayrollCalculator();
$brk  = $calc->calculate($e);

$text = PayrollFactory::createPayslipRenderer('text')->render($e, $brk);
echo $text, PHP_EOL;
