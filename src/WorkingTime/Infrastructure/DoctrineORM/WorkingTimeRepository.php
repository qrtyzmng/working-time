<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineORM;

use App\WorkingTime\Domain\Entity\WorkingTime;
use App\WorkingTime\Domain\Repository\WorkingTimeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class WorkingTimeRepository extends ServiceEntityRepository implements WorkingTimeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingTime::class);
    }

    public function findByEmployeesStartDate(string $employeeUuid, \DateTime $startDate): ?WorkingTime
    {
        return $this->findOneBy(['employee' => $employeeUuid, 'startDay' => $startDate]);
    }

    /**
     * @return WorkingTime[]
     */
    public function findForMonthlyReport(string $employeeUuid, \DateTime $monthlyFormattedDate): array
    {
        $startDate = $monthlyFormattedDate->modify('first day of this month');
        $endDate = (new \DateTimeImmutable($monthlyFormattedDate->format('Y-m-d')))->modify('last day of this month');
        $endDate = $endDate->modify('last day of this month');

        $qb = $this->createQueryBuilder('w')
            ->where('w.employee = :employee AND w.startDay BETWEEN :startDate AND :endDate')
            ->setParameter('employee', Uuid::fromString($employeeUuid)->toBinary())
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return $qb->getQuery()->getResult();
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
