<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\ValueObject;

interface UuidInterface
{
    public function getId(): string;

    public static function create(): self;

    public static function fromString(string $uuid): self;

    public function __toString(): string;
}
