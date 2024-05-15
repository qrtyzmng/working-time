<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Factory;

use App\WorkingTime\Domain\Entity\Employee;

interface EmployeeFactoryInterface
{
    public function create(string $uuid, string $firstname, string $lastname): Employee;
}
