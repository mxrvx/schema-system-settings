<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class ArrayUniqueCaster extends TypeCaster
{
    public static function transform(?array $value, bool $skipEmpty = true): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = \array_values(\array_unique($value, SORT_REGULAR));

        if ($skipEmpty && $value === []) {
            return null;
        }

        return $value;
    }

    public static function getTransformers(): array
    {
        return [
            [ArrayCaster::class, 'transform'],
            [ArrayUniqueCaster::class, 'transform'],
        ];
    }
}
