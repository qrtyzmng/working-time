<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Handler\Employee;

use App\WorkingTime\Application\Command\Employee\CreateCommand;
use App\WorkingTime\Domain\Factory\EmployeeFactoryInterface;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateHandler
{
    public function __construct(private EmployeeRepositoryInterface $repository, private EmployeeFactoryInterface $factory)
    {
    }

    public function __invoke(CreateCommand $createCommand): void
    {
        $employee = $this->factory->create($createCommand->uuid, $createCommand->firstname, $createCommand->lastname);
        $this->repository->create($employee);
    }
}
