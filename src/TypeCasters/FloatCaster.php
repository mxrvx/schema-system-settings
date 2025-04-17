<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class FloatCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?float
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'integer' => (float) $value,
            'string' =>  \is_numeric(\trim($value)) ? (float) $value : null,
            'boolean' => (float) ($value === true ? 1 : 0),
            'double' => $value,
            default => null,
        };

        if ($skipEmpty && ($value === null || $value === 0.0)) {
            return null;
        }

        return \is_float($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [FloatCaster::class, 'transform'],
        ];
    }
}
