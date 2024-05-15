<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Repository;

use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\ValueObject\UuidInterface;

interface WorkingTimeRepositoryInterface
{
    public function findByEmployeesStartDate(UuidInterface $employeeUuid, \DateTime $startDate): ?WorkingTime;

    public function create(WorkingTime $workingTime): void;

    public function save(): void;
}
