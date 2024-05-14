<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Repository;

use App\WorkingTime\Domain\Entity\Employee;

interface EmployeeRepositoryInterface
{
    public function create(Employee $employee): void;

    public function save(): void;
}
