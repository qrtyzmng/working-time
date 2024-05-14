<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Entity;

use App\WorkingTime\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\Entity]
class WorkingTime
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $uuid;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'workingTimes')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'uuid')]
    private Employee $employee;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $startDateTime;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $endDateTime;

    #[ORM\Column(type: 'date')]
    private \DateTime $startDay;
}
