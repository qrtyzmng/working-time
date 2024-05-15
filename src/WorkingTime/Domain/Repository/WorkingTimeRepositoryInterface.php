<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Repository;

use App\WorkingTime\Domain\Entity\WorkingTime;

interface WorkingTimeRepositoryInterface
{
    public function findByEmployeesStartDate(string $employeeUuid, \DateTime $startDate): ?WorkingTime;

    /**
     * @return WorkingTime[]
     */
    public function findForMonthlyReport(string $employeeUuid, \DateTime $monthlyFormattedDate): array;

    public function create(WorkingTime $workingTime): void;

    public function save(): void;
}
