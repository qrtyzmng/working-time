<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Factory;

use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\ValueObject\UuidInterface;

interface EmployeeFactoryInterface
{
    public function create(UuidInterface $uuid, string $firstname, string $lastname): Employee;
}
