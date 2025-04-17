<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class IntegerCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?int
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'double' => (int) $value,
            'string' =>  \is_numeric(\trim($value)) ? (int) $value : null,
            'boolean' => $value === true ? 1 : 0,
            'integer' => $value,
            default => null,
        };

        if ($skipEmpty && ($value === null || $value === 0)) {
            return null;
        }

        return \is_int($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [IntegerCaster::class, 'transform'],
        ];
    }
}
