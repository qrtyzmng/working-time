<?php

declare(strict_types=1);

namespace Tests\Unit\Factory;

use App\WorkingTime\Domain\Factory\EmployeeFactory;
use PHPUnit\Framework\TestCase;

final class EmployeeFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_employee(): void
    {
        $factory = new EmployeeFactory();
        $uuid = '102e0bc2-ccd0-425e-95ba-07299cb4b9b0';
        $firstname = 'John';
        $lastname = 'Doe';
        $employee = $factory->create($uuid, $firstname, $lastname);
        $this->assertSame($employee->getUuid(), $uuid);
        $this->assertSame($employee->getFirstname(), $firstname);
        $this->assertSame($employee->getLastname(), $lastname);
    }
}
