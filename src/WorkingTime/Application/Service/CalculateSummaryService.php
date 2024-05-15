<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Service;

use App\WorkingTime\Domain\Entity\WorkingTime;

final readonly class CalculateSummaryService implements CalculateSummaryServiceInterface
{
    public function __construct(private int $hourlyRate)
    {
    }

    public function calculate(WorkingTime $workingTime): array
    {
        $startDateTime = $workingTime->getStartDateTime();
        $endDateTime = $workingTime->getEndDateTime();
        $totalMinutes = $this->convertToMinutes($startDateTime, $endDateTime);

        return [
            'id' => $workingTime->getUuid(),
            'startDateTime' => $startDateTime->format('Y-m-d H:i:s'),
            'endDateTime' => $endDateTime->format('Y-m-d H:i:s'),
            'totalMinutes' => $totalMinutes,
            'total' => $this->calculateRoundedTotal($totalMinutes),
        ];
    }

    private function convertToMinutes(\DateTime $startDateTime, \DateTime $endDateTime): int
    {
        return \abs($startDateTime->getTimestamp() - $endDateTime->getTimestamp()) / 60;
    }

    private function calculateRoundedTotal(int $totalMinutes): int|float
    {
        $totalHours = $totalMinutes / 60;

        return \floor(($totalHours * 2) / 2) * $this->hourlyRate;
    }
}
