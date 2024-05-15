<?php

declare(strict_types=1);

namespace Tests\Unit\Handler\WorkingTime;

use App\WorkingTime\Application\Handler\WorkingTime\SummaryHandler;
use App\WorkingTime\Application\Query\WorkingTime\SummaryQuery;
use App\WorkingTime\Application\Service\CalculateSummaryServiceInterface;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Exception\ResourceNotFoundException;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class SummaryHandlerTest extends TestCase
{
    private EmployeeRepositoryInterface&MockObject $repository;
    private WorkingTimeRepositoryInterface&MockObject $workingTimeRepository;
    private CalculateSummaryServiceInterface&MockObject $calculateService;
    private SummaryHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmployeeRepositoryInterface::class);
        $this->workingTimeRepository = $this->createMock(WorkingTimeRepositoryInterface::class);
        $this->calculateService = $this->createMock(CalculateSummaryServiceInterface::class);
        $this->handler = new SummaryHandler($this->repository, $this->workingTimeRepository, $this->calculateService);
    }

    /**
     * @test
     */
    public function it_returns_resource_not_found_exception(): void
    {
        $query = $this->getQuery();

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($query->employeeUuid)
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Employee not found');

        ($this->handler)($query);
    }

    /**
     * @test
     */
    public function it_returns_resource_not_found_on_invalid_date_format(): void
    {
        $query = $this->getQuery();
        $query->date = '2022';

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($query->employeeUuid)
            ->willReturn($this->createMock(Employee::class));

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('WorkingTime not found for provided date format');

        ($this->handler)($query);
    }

    /**
     * @test
     */
    public function it_returns_resource_not_found_when_not_added(): void
    {
        $query = $this->getQuery();
        $employee = $this->createMock(Employee::class);

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($query->employeeUuid)
            ->willReturn($employee);

        $employee->expects($this->once())
            ->method('getUuid')
            ->willReturn(UuidV4::fromString($query->employeeUuid));

        $this->workingTimeRepository->expects($this->once())
            ->method('findByEmployeesStartDate')
            ->with($query->employeeUuid, \DateTime::createFromFormat('Y-m-d', $query->date))
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('WorkingTime not found');

        ($this->handler)($query);
    }

    /**
     * @test
     */
    public function it_will_calculate_summary_for_exact_date(): void
    {
        $query = $this->getQuery();
        $employee = $this->createMock(Employee::class);

        $this->repository->expects($this->once())
            ->method('findByUuid')
            ->with($query->employeeUuid)
            ->willReturn($employee);

        $employee->expects($this->once())
            ->method('getUuid')
            ->willReturn(UuidV4::fromString($query->employeeUuid));

        $workingTime = $this->createMock(WorkingTime::class);
        $this->workingTimeRepository->expects($this->once())
            ->method('findByEmployeesStartDate')
            ->with($query->employeeUuid, \DateTime::createFromFormat('Y-m-d', $query->date))
            ->willReturn($workingTime);

        $this->calculateService->expects($this->once())
            ->method('calculate')
            ->with($workingTime);

        ($this->handler)($query);
    }

    private function getQuery(): SummaryQuery
    {
        $query = new SummaryQuery();
        $query->employeeUuid = Uuid::v4()->toRfc4122();
        $query->date = '2024-12-12';

        return $query;
    }
}
