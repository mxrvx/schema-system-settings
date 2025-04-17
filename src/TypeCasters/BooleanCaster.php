<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class BooleanCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?bool
    {
        if ($value === null) {
            return null;
        }

        $value = match (\gettype($value)) {
            'integer' => \in_array($value, [0,1], true) ? $value === 1 : null,
            'string' => \in_array((\trim($value)), ['true', 'TRUE', '1', 'false', 'FALSE', '0'], true) ? \in_array(
                (\trim($value)),
                ['true', 'TRUE', '1'],
                true,
            ) : null,
            'boolean' => $value,
            default => null,
        };

        if ($skipEmpty && $value === null) {
            return null;
        }

        return \is_bool($value) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [BooleanCaster::class, 'transform'],
        ];
    }
}
