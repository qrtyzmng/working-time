<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineDBAL;

use App\WorkingTime\Domain\Exception\InvalidUuidException;
use App\WorkingTime\Domain\ValueObject\UuidInterface;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

final class UuidV4 implements UuidInterface
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function create(): self
    {
        return new self(
            SymfonyUuid::v4()->toRfc4122()
        );
    }

    public static function fromString(string $uuid): self
    {
        if (false === SymfonyUuid::isValid($uuid)) {
            throw InvalidUuidException::create($uuid);
        }

        return new self($uuid);
    }

    public function __toString(): string
    {
        return $this->getId();
    }
}
