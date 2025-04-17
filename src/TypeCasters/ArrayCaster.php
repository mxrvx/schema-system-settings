<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class ArrayCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'string' => (!empty($value) && ($value[0] === '[' || $value[0] === '{')) ? (array) \json_decode($value, true) : [],
            'array' => $value,
            default => null,
        };

        if ($skipEmpty && ($value === null || $value === [])) {
            return null;
        }

        return \is_array($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [ArrayCaster::class, 'transform'],
        ];
    }
}
