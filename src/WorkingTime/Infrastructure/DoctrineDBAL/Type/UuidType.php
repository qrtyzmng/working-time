<?php

declare(strict_types=1);

namespace App\WorkingTime\Infrastructure\DoctrineDBAL\Type;

use App\WorkingTime\Domain\Exception\InvalidUuidException;
use App\WorkingTime\Domain\ValueObject\UuidInterface;
use App\WorkingTime\Infrastructure\DoctrineDBAL\UuidV4;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
    public const string NAME = 'uuid_v4';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL([
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidInterface
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof UuidInterface) {
            return $value;
        }

        try {
            $uuid = UuidV4::fromString($value);
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
        if ($value instanceof UuidInterface) {
            return $value->getId();
        }

        try {
            if (\is_string($value)) {
                return UuidV4::fromString($value)->getId();
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
