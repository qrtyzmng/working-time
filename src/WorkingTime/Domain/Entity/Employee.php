<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Entity;

use App\WorkingTime\Domain\ValueObject\Uuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\Entity]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $uuid;

    #[ORM\Column(type: 'string', length: 32)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 32)]
    private string $lastname;

    #[ORM\OneToMany(targetEntity: WorkingTime::class, mappedBy: 'employee')]
    private Collection $workingTimes;
}
