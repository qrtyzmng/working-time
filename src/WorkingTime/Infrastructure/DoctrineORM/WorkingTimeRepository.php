<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineORM;

use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use App\WorkingTime\Domain\ValueObject\UuidInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkingTimeRepository extends ServiceEntityRepository implements WorkingTimeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingTime::class);
    }

    public function findByEmployeesStartDate(UuidInterface $employeeUuid, \DateTime $startDate): ?WorkingTime
    {
        return $this->findOneBy(['employee' => $employeeUuid, 'startDay' => $startDate]);
    }

    public function create(WorkingTime $workingTime): void
    {
        $em = $this->getEntityManager();
        $em->persist($workingTime);
        $this->save();
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
