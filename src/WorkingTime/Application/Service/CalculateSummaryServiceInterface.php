<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Service;

use App\WorkingTime\Domain\Entity\WorkingTime;

interface CalculateSummaryServiceInterface
{
    public function calculate(WorkingTime $workingTime): array;
}
