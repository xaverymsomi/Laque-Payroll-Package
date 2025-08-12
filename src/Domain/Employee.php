<?php

namespace Laque\Payroll\Domain;

/**
 * Domain model for Employee
 */
class Employee
{
	private ?string $position = null;
    /**
     * @var string The employee ID
     */
    private string $employeeId;
    
    /**
     * @var string The first name
     */
    private string $firstName;
    
    /**
     * @var string The last name
     */
    private string $lastName;
    
    /**
     * @var float The basic salary
     */
    private float $basicSalary;
    
    /**
     * @var array<Earning> The earnings
     */
    private array $earnings;
    
    /**
     * @var array<Deduction> The deductions
     */
    private array $deductions;
    
    /**
     * Create a new employee
     * 
     * @param string $employeeId The employee ID
     * @param string $firstName The first name
     * @param string $lastName The last name
     * @param float $basicSalary The basic salary
     * @param array<Earning> $earnings The earnings
     * @param array<Deduction> $deductions The deductions
     */
    public function __construct(
        string $employeeId,
        string $firstName,
        string $lastName,
        float $basicSalary,
        array $earnings = [],
        array $deductions = [],
	    ?string $position = null
    ) {
        $this->employeeId = $employeeId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->basicSalary = $basicSalary;
        $this->earnings = $earnings;
        $this->deductions = $deductions;
	    $this->position = $position;
    }
    
    /**
     * Get the employee ID
     * 
     * @return string The employee ID
     */
    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }
    
    /**
     * Get the first name
     * 
     * @return string The first name
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    /**
     * Get the last name
     * 
     * @return string The last name
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
    
    /**
     * Get the full name
     * 
     * @return string The full name
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    
    /**
     * Get the basic salary
     * 
     * @return float The basic salary
     */
    public function getBasicSalary(): float
    {
        return $this->basicSalary;
    }
    
    /**
     * Get the earnings
     * 
     * @return array<Earning> The earnings
     */
    public function getEarnings(): array
    {
        return $this->earnings;
    }
    
    /**
     * Get the deductions
     * 
     * @return array<Deduction> The deductions
     */
    public function getDeductions(): array
    {
        return $this->deductions;
    }
    
    /**
     * Get the total earnings
     * 
     * @return float The total earnings
     */
    public function getTotalEarnings(): float
    {
        $total = $this->basicSalary;
        foreach ($this->earnings as $earning) {
            $total += $earning->getAmount();
        }
        return $total;
    }
    
    /**
     * Add an earning
     * 
     * @param Earning $earning The earning
     * @return self
     */
    public function addEarning(Earning $earning): self
    {
        $this->earnings[] = $earning;
        return $this;
    }
    
    /**
     * Add a deduction
     * 
     * @param Deduction $deduction The deduction
     * @return self
     */
    public function addDeduction(Deduction $deduction): self
    {
        $this->deductions[] = $deduction;
        return $this;
    }

	// Sum of additional earnings (excludes basic)
	public function getTotalAdditionalEarnings(): float
	{
		$sum = 0.0;
		foreach ($this->earnings as $e) {
			$sum += $e->getAmount();
		}
		return $sum;
	}

	// Gross = basic + additional earnings
	public function getGrossSalary(): float
	{
		return $this->basicSalary + $this->getTotalAdditionalEarnings();
	}

	// Optional: personal deductions total (handy for net pay calcs)
	public function getTotalPersonalDeductions(): float
	{
		$sum = 0.0;
		foreach ($this->deductions as $d) {
			$sum += $d->getAmount();
		}
		return $sum;
	}
	// 3) Add accessor + alias
	public function getPosition(): string
	{
		return $this->position ?? '';
	}

	public function setPosition(string $position): self
	{
		$this->position = $position;
		return $this;
	}

	// (Optional alias if other code calls it)
	public function getTitle(): string
	{
		return $this->getPosition();
	}


}