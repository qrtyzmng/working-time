<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Handler\WorkingTime;

use App\WorkingTime\Application\Query\WorkingTime\SummaryQuery;
use App\WorkingTime\Application\Service\CalculateSummaryServiceInterface;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Exception\ResourceNotFoundException;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SummaryHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $repository,
        private WorkingTimeRepositoryInterface $workingTimeRepository,
        private CalculateSummaryServiceInterface $calculateSummaryService,
    ) {
    }

    public function __invoke(SummaryQuery $query): array
    {
        $employee = $this->repository->findByUuid($query->employeeUuid);
        if (! $employee instanceof Employee) {
            throw new ResourceNotFoundException(message: 'Employee not found');
        }

        $dailyFormatDate = \DateTime::createFromFormat('Y-m-d', $query->date);
        if ($dailyFormatDate) {
            $workingTime = $this->workingTimeRepository->findByEmployeesStartDate($employee->getUuid(), $dailyFormatDate);
            if (! $workingTime instanceof WorkingTime) {
                throw new ResourceNotFoundException(message: 'WorkingTime not found');
            }

            return $this->calculateSummaryService->calculate($workingTime);
        }

        throw new ResourceNotFoundException(message: 'WorkingTime not found for provided date format');
    }
}
