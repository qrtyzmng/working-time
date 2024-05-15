<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Factory;

use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\ValueObject\UuidInterface;

class EmployeeFactory implements EmployeeFactoryInterface
{
    public function create(UuidInterface $uuid, string $firstname, string $lastname): Employee
    {
        return new Employee($uuid, $firstname, $lastname);
    }
}
