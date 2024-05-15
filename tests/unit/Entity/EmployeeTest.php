<?php

declare(strict_types=1);

namespace Tests\Unit\Entity;

use App\WorkingTime\Domain\Entity\Employee;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use PHPUnit\Framework\TestCase;

final class EmployeeTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_init_employee(): void
    {
        $uuid = UuidV4::create();
        $firstname = 'John';
        $lastname = 'Doe';
        $employee = new Employee($uuid, $firstname, $lastname);
        $this->assertSame($uuid, $employee->getUuid());
        $this->assertSame($firstname, $employee->getFirstname());
        $this->assertSame($lastname, $employee->getLastname());
    }
}
