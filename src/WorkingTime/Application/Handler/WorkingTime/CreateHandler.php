<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Handler\WorkingTime;

use App\WorkingTime\Application\Command\WorkingTime\CreateCommand;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Exception\InvalidDataRangeException;
use App\WorkingTime\Domain\Exception\ResourceNotFoundException;
use App\WorkingTime\Domain\Exception\StartDateAlreadyExistingException;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateHandler
{
    private const int HOURLY_LIMIT_PER_DAY = 12;

    public function __construct(
        private EmployeeRepositoryInterface $repository,
        private WorkingTimeRepositoryInterface $workingTimeRepository,
    ) {
    }

    public function __invoke(CreateCommand $createCommand): void
    {
        $employee = $this->repository->findByUuid($createCommand->employeeUuid);
        if (! $employee instanceof Employee) {
            throw new ResourceNotFoundException(message: 'Employee not found');
        }

        $startDateTime = \DateTime::createFromFormat(\DateTime::ATOM, $createCommand->startDateTime);
        $endDateTime = \DateTime::createFromFormat(\DateTime::ATOM, $createCommand->endDateTime);

        if ($endDateTime->diff($startDateTime)->h > self::HOURLY_LIMIT_PER_DAY) {
            throw new InvalidDataRangeException('Data range exceeds 12 hours');
        }

        $workingTime = $this->workingTimeRepository->findByEmployeesStartDate($employee->getUuid(), $startDateTime);

        if ($workingTime instanceof WorkingTime) {
            throw new StartDateAlreadyExistingException(message: 'Start date already exists for this date');
        }

        $this->workingTimeRepository->create(
            new WorkingTime(
                $startDateTime,
                $endDateTime,
                $employee,
            ),
        );
    }
}
