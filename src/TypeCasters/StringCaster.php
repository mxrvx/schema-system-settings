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
            'array' => self::arrayToString($value),
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

    public static function arrayToString(array $value): ?string
    {
        try {
            $string = \json_encode($value, JSON_THROW_ON_ERROR);
            /** @var array|null $value */
            $value = \json_decode($string, true);
            if (\json_last_error() !== JSON_ERROR_NONE) {
                $value = [];
            }
        } catch (\JsonException $e) {
            return null;
        }

        if (empty($value)) {
            return null;
        }

        if (\array_keys($value) === \range(0, \count($value) - 1)) {
            $count = \count($value);
            $tmp = \array_filter($value, static fn($v) => \is_scalar($v) || \is_null($v));
            if (\count($tmp) === $count) {
                return \implode(',', $tmp);
            }
        }

        return \json_encode($value, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);
    }
}
