<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class ArrayIntegerCaster extends TypeCaster
{
    public static function transform(?array $value, bool $skipEmpty = true): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = \array_filter($value, 'is_scalar');
        $value = \array_map('intval', $value);

        if ($skipEmpty && $value === []) {
            return null;
        }

        return \array_values($value);
    }

    public static function getTransformers(): array
    {
        return [
            [ArrayCaster::class, 'transform'],
            [ArrayIntegerCaster::class, 'transform'],
        ];
    }
}
