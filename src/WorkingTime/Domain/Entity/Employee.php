<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\Entity]
class Employee
{
    #[ORM\OneToMany(targetEntity: WorkingTime::class, mappedBy: 'employee')]
    private Collection $workingTimes;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string $uuid,
        #[ORM\Column(type: 'string', length: 32)]
        private string $firstname,
        #[ORM\Column(type: 'string', length: 32)]
        private string $lastname,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}
