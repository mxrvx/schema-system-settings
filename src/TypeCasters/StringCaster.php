<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class StringCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'boolean' => $value ? '1' : '0',
            'integer', 'double' => (string) $value,
            'array' => !empty($value) ? \json_encode($value, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES) : null,
            'string' => \trim($value),
            default => null,
        };

        if ($skipEmpty && ($value === null || $value === '')) {
            return null;
        }

        return \is_string($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [StringCaster::class, 'transform'],
        ];
    }
}
