<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Repository;

use App\WorkingTime\Domain\Entity\WorkingTime;

interface WorkingTimeRepositoryInterface
{
    public function findByEmployeesStartDate(string $employeeUuid, \DateTime $startDate): ?WorkingTime;

    public function create(WorkingTime $workingTime): void;

    public function save(): void;
}
