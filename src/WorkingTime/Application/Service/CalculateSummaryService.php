<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Service;

use App\WorkingTime\Domain\Entity\WorkingTime;

final class CalculateSummaryService
{
    private int $totalMinutes = 0;

    public function __construct(private int $monthlyHourlyNorm, private int $hourlyRate, private int $overTimeRate)
    {
    }

    /**
     * @param WorkingTime[] $workingTimes
     */
    public function calculateAll(array $workingTimes)
    {
        foreach ($workingTimes as $workingTime) {
            $this->calculate($workingTime);
        }

        $overTimeTotalMinutes = \max($this->totalMinutes - ($this->monthlyHourlyNorm * 60), 0);
        $monthlyOvertimeTotal = $this->calculateRoundedTotal($overTimeTotalMinutes, $this->overTimeRate);
        $monthlyBaseTotal = $this->calculateRoundedTotal($this->totalMinutes - $overTimeTotalMinutes);

        return [
            'monthlyTotalMinutes' => $this->totalMinutes,
            'overTimeTotalMinutes' => $overTimeTotalMinutes,
            'monthlyOvertimeTotal' => $this->calculateRoundedTotal($overTimeTotalMinutes, $this->overTimeRate),
            'monthlyBaseTotal' => $this->calculateRoundedTotal($this->totalMinutes - $overTimeTotalMinutes),
            'total' => $monthlyOvertimeTotal + $monthlyBaseTotal,
        ];
    }

    public function calculate(WorkingTime $workingTime): array
    {
        $startDateTime = $workingTime->getStartDateTime();
        $endDateTime = $workingTime->getEndDateTime();
        $totalMinutes = $this->convertToMinutes($startDateTime, $endDateTime);
        $this->totalMinutes += $totalMinutes;

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

    private function calculateRoundedTotal(int $totalMinutes, int $overTimeRate = 1): int|float
    {
        $totalHours = $totalMinutes / 60;

        return \floor(($totalHours * 2) / 2) * ($this->hourlyRate * $overTimeRate);
    }
}
