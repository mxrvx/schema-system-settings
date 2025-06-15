<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class ListCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'string' => (!empty($value) && ($value[0] === '[' || $value[0] === '{')) ? (array) \json_decode($value, true) : \explode(',', $value),
            'array' => $value,
            default => null,
        };

        if (\is_array($value)) {
            $value =  \array_filter($value, 'is_scalar');
            $value = \array_map(static function ($value) {
                return \is_string($value) ? \trim($value) : $value;
            }, $value);
            $value =  \array_filter($value, static fn($value) => $value !== '');
        }

        if ($skipEmpty && ($value === null || $value === [])) {
            return null;
        }

        return \is_array($value) ? \array_values($value) : null;
    }

    public static function getTransformers(): array
    {
        return [
            [ListCaster::class, 'transform'],
            //[ArrayUniqueCaster::class, 'transform'],
        ];
    }
}
