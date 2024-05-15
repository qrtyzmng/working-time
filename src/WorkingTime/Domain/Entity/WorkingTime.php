<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Entity;

use App\WorkingTime\Domain\ValueObject\UuidInterface;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\Entity]
class WorkingTime
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_v4', unique: true)]
    private UuidInterface $uuid;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'workingTimes')]
    #[ORM\JoinColumn(name: 'employee_uuid', referencedColumnName: 'uuid')]
    private Employee $employee;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $startDateTime;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $endDateTime;

    #[ORM\Column(type: 'date')]
    private \DateTime $startDay;

    public function __construct(\DateTime $startDateTime, \DateTime $endDateTime, Employee $employee)
    {
        $this->uuid = UuidV4::create();
        $this->startDay = $startDateTime;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->employee = $employee;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getStartDateTime(): \DateTime
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): \DateTime
    {
        return $this->endDateTime;
    }
}
