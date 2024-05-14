<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table]
#[ORM\Entity]
class WorkingTime
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $uuid;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'workingTimes')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'uuid')]
    private Employee $employee;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $startDateTime;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $endDateTime;

    #[ORM\Column(type: 'date')]
    private \DateTime $startDay;

    public function __construct(\DateTime $startDateTime, \DateTime $endDateTime, Employee $employee)
    {
        $this->uuid = Uuid::v4()->toRfc4122();
        $this->startDay = $startDateTime;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->employee = $employee;
    }
}
