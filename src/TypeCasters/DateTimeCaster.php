<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

class DateTimeCaster extends TypeCaster
{
    public static function transform(mixed $value, bool $skipEmpty = true): ?\DateTime
    {
        if ($value === null) {
            return null;
        }

        try {
            $value = match (\gettype($value)) {
                'integer' => new \DateTime('@' . $value),
                'string' => \is_numeric(\trim($value)) ? new \DateTime('@' . $value) : (!empty(\trim($value)) ? new \DateTime($value) : null),
                'object' => \is_a($value, \DateTime::class) && !empty($value->getTimestamp()) ? new \DateTime('@' . $value->getTimestamp()) : null,
                default => null,
            };
        } catch (\Throwable $e) {
            $value = null;
        }

        if ($skipEmpty && ($value === null || ((int) $value->format('Y') < 0))) {
            return null;
        }

        return \is_a($value, \DateTime::class) ? $value : null;
    }

    public static function getTransformers(): array
    {
        return [
            [DateTimeCaster::class, 'transform'],
        ];
    }
}
