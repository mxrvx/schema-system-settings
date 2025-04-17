<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class JsonCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'string' => (!empty($value) && ($value[0] === '[' || $value[0] === '{')) ? \json_encode(
                \json_decode($value, true) ?? [],
                \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES,
            ) : null,
            'array' => \json_encode($value, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES),
            default => null,
        };

        if ($skipEmpty && ($value === null || $value === '[]')) {
            return null;
        }

        return \is_string($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [JsonCaster::class, 'transform'],
        ];
    }
}
