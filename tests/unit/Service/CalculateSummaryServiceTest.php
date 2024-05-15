<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\WorkingTime\Application\Service\CalculateSummaryService;
use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Domain\Entity\WorkingTime;
use PHPUnit\Framework\TestCase;

final class CalculateSummaryServiceTest extends TestCase
{
    private CalculateSummaryService $calculateService;

    protected function setUp(): void
    {
        $this->calculateService = new CalculateSummaryService(20);
    }

    /**
     * @test
     */
    public function it_can_calculate_totals(): void
    {
        $workingTime = new WorkingTime(
            new \DateTime('2022-12-12 12:42:00'),
            new \DateTime('2022-12-12 18:45:00'),
            $this->createMock(Employee::class),
        );

        $calculations = $this->calculateService->calculate($workingTime);
        $this->assertSame($workingTime->getUuid(), $calculations['id']);
        $this->assertSame($workingTime->getStartDateTime()->format('Y-m-d H:i:s'), $calculations['startDateTime']);
        $this->assertSame($workingTime->getEndDateTime()->format('Y-m-d H:i:s'), $calculations['endDateTime']);
        $this->assertSame(363, $calculations['totalMinutes']);
        $this->assertSame(120.0, $calculations['total']);
    }
}
