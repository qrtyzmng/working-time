<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Command\WorkingTime;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $employeeUuid;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: \DateTimeInterface::ATOM)]
    public string $startDateTime;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: \DateTimeInterface::ATOM)]
    public string $endDateTime;
}
