<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class ListStringCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?array
    {
        if ($value === null) {
            return null;
        }

        if (\is_array($value)) {
            $value = \array_map(static function ($value) {
                return \is_scalar($value) ? \trim((string) $value) : null;
            }, $value);
            $value = \array_filter($value);
            $value = \array_values(\array_unique($value, SORT_REGULAR));
        }

        if ($skipEmpty && ($value === null || $value === [])) {
            return null;
        }

        return \is_array($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [ListCaster::class, 'transform'],
            [ListStringCaster::class, 'transform'],
        ];
    }
}
