<?php

declare(strict_types=1);

namespace Tests\Unit\Handler\Employee;

use App\WorkingTime\Application\Command\Employee\CreateCommand;
use App\WorkingTime\Application\Handler\Employee\CreateHandler;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Factory\EmployeeFactoryInterface;
use App\WorkingTime\Infrastructure\DoctrineORM\EmployeeRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CreateHandlerTest extends TestCase
{
    private EmployeeRepository&MockObject $repository;
    private EmployeeFactoryInterface&MockObject $factory;
    private CreateHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmployeeRepository::class);
        $this->factory = $this->createMock(EmployeeFactoryInterface::class);
        $this->handler = new CreateHandler($this->repository, $this->factory);
    }

    /**
     * @test
     */
    public function it_creates_employee(): void
    {
        $command = new CreateCommand();
        $command->uuid = Uuid::v4()->toRfc4122();
        $command->firstname = 'John';
        $command->lastname = 'Doue';

        $employee = $this->createMock(Employee::class);

        $this->factory->expects($this->once())
            ->method('create')
            ->with($command->uuid, $command->firstname, $command->lastname)
            ->willReturn($employee);

        $this->repository->expects($this->once())
            ->method('create')
            ->with($employee);

        ($this->handler)($command);
    }
}
