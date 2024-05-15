<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Query\WorkingTime;

use Symfony\Component\Validator\Constraints as Assert;

class SummaryQuery
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public ?string $employeeUuid;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?string $date;
}
