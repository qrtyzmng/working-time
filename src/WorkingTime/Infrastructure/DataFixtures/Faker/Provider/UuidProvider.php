<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DataFixtures\Faker\Provider;

use App\WorkingTime\Domain\ValueObject\UuidInterface;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use Faker\Provider\Base;

class UuidProvider extends Base
{
    public function uuidObject(?string $uuid = null): UuidInterface
    {
        return $uuid ? UuidV4::fromString($uuid) : UuidV4::create();
    }
}
