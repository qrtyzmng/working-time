<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineORM;

use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Repository\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findByUuid(string $uuid): ?Employee
    {
        return $this->find($uuid);
    }

    public function create(Employee $employee): void
    {
        $em = $this->getEntityManager();
        $em->persist($employee);
        $this->save();
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
