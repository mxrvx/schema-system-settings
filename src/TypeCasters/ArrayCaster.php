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
            'string' => self::stringToArray($value),
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

    public static function stringToArray(string $value): ?array
    {
        if (empty($value)) {
            return null;
        }

        if (($value[0] === '[' || $value[0] === '{')) {
            /** @var array|null $array */
            $array = \json_decode($value, true);
            if (\json_last_error() !== JSON_ERROR_NONE) {
                $array = [];
            }
        } else {
            $array = \explode(',', $value);
            $array = \array_filter($array, static fn($v) => \is_scalar($v));
        }

        return $array;
    }
}
