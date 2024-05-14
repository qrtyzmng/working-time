<?php

declare(strict_types=1);

namespace App\WorkingTime\Domain\ValueObject\Uuid;

class InvalidUuidException extends \InvalidArgumentException
{
    public static function create(string $unit): self
    {
        return new self(\sprintf(
            "'%s' is not valid UUid.",
            $unit
        ));
    }
}
