<?php

declare(strict_types=1);

namespace Tests\Unit\Handler\WorkingTime;

use App\WorkingTime\Application\Command\WorkingTime\CreateCommand;
use App\WorkingTime\Application\Handler\WorkingTime\CreateHandler;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Exception\InvalidDataRangeException;
use App\WorkingTime\Domain\Exception\ResourceNotFoundException;
use App\WorkingTime\Domain\Exception\StartDateAlreadyExistingException;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CreateHandlerTest extends TestCase
{
    private EmployeeRepositoryInterface&MockObject $repository;
    private WorkingTimeRepositoryInterface&MockObject $workingTimeRepository;
    private CreateHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmployeeRepositoryInterface::class);
        $this->workingTimeRepository = $this->createMock(WorkingTimeRepositoryInterface::class);
        $this->handler = new CreateHandler($this->repository, $this->workingTimeRepository);
    }

    /**
     * @test
     */
    public function it_returns_resource_not_found_exception(): void
    {
        $command = $this->getCommand();

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($command->employeeUuid)
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Employee not found');

        ($this->handler)($command);
    }

    /**
     * @test
     */
    public function it_returns_invalid_data_range_exception(): void
    {
        $command = $this->getCommand();
        $command->startDateTime = '2024-12-12T02:20:00P';

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($command->employeeUuid)
            ->willReturn($this->createMock(Employee::class));

        $this->expectException(InvalidDataRangeException::class);
        $this->expectExceptionMessage('Data range exceeds 12 hours');

        ($this->handler)($command);
    }

    /**
     * @test
     */
    public function it_returns_start_date_already_existing_exception(): void
    {
        $command = $this->getCommand();
        $employee = $this->createMock(Employee::class);

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($command->employeeUuid)
            ->willReturn($employee);

        $employee->expects($this->once())
            ->method('getUuid')
            ->willReturn(UuidV4::fromString($command->employeeUuid));

        $this->workingTimeRepository->expects($this->once())
            ->method('findByEmployeesStartDate')
            ->with($command->employeeUuid, \DateTime::createFromFormat(\DateTime::ATOM, $command->startDateTime))
            ->willReturn($this->createMock(WorkingTime::class));

        $this->expectException(StartDateAlreadyExistingException::class);
        $this->expectExceptionMessage('Start date already exists for this date');

        ($this->handler)($command);
    }

    /**
     * @test
     */
    public function it_will_create_working_time(): void
    {
        $command = $this->getCommand();
        $employee = $this->createMock(Employee::class);

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($command->employeeUuid)
            ->willReturn($employee);

        $employee->expects($this->once())
            ->method('getUuid')
            ->willReturn(UuidV4::fromString($command->employeeUuid));

        $this->workingTimeRepository->expects($this->once())
            ->method('findByEmployeesStartDate')
            ->with($command->employeeUuid, \DateTime::createFromFormat(\DateTime::ATOM, $command->startDateTime))
            ->willReturn(null);

        $this->workingTimeRepository->expects($this->once())
            ->method('create');

        ($this->handler)($command);
    }

    private function getCommand(): CreateCommand
    {
        $command = new CreateCommand();
        $command->employeeUuid = Uuid::v4()->toRfc4122();
        $command->startDateTime = '2024-12-12T13:20:00P';
        $command->endDateTime = '2024-12-12T15:20:00P';

        return $command;
    }
}
