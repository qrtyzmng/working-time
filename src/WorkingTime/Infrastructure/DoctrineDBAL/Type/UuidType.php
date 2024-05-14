<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineDBAL\Type;

use App\WorkingTime\Domain\Exception\InvalidUuidException;
use App\WorkingTime\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
    public const string NAME = 'uuid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL([
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value;
        }

        try {
            $uuid = Uuid::fromString($value);
        } catch (InvalidUuidException $e) {
            throw ValueNotConvertible::new(value: $value, toType: static::NAME, previous: $e);
        }

        return $uuid;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }
        if ($value instanceof Uuid) {
            return $value->getId();
        }

        try {
            if (\is_string($value)) {
                return Uuid::fromString($value)->getId();
            }
        } catch (InvalidUuidException) {
            // Ignore the exception and pass through.
        }

        throw ValueNotConvertible::new(value: $value, toType: static::NAME);
    }

    public function getName(): string
    {
        return static::NAME;
    }
}
