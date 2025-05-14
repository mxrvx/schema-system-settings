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

    public static function arrayToString(array $array): ?string
    {
        try {
            $json = \json_encode($array, JSON_THROW_ON_ERROR);
            /** @var array|null $array */
            $array = \json_decode($json, true);
            if (\json_last_error() !== JSON_ERROR_NONE) {
                $array = [];
            }
        } catch (\JsonException $e) {
            return null;
        }

        if (empty($array)) {
            return null;
        }

        if (\array_keys($array) === \range(0, \count($array) - 1)) {
            $count = \count($array);
            $array = \array_filter($array, static fn($v) => \is_scalar($v) || \is_null($v));
            if (\count($array) === $count) {
                return \implode(',', $array);
            }
        }

        return \json_encode($array, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);
    }

}
