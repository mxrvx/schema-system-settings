<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

trait SettingTrait
{
    public function getStringValue(mixed $default = null, bool $skipEmpty = true): ?string
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::STRING]);

        return \is_string($value) ? $value : null;
    }

    public function getIntegerValue(mixed $default = null, bool $skipEmpty = true): ?int
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::INTEGER]);

        return \is_int($value) ? $value : null;
    }

    public function getFloatValue(mixed $default = null, bool $skipEmpty = true): ?float
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::FLOAT]);

        return \is_float($value) ? $value : null;
    }

    public function getBoolValue(mixed $default = null, bool $skipEmpty = true): ?bool
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::BOOLEAN]);

        return \is_bool($value) ? $value : null;
    }

    public function getDateTimeValue(mixed $default = null, bool $skipEmpty = true): ?\DateTime
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::DATETIME]);

        return \is_a($value, \DateTime::class) ? $value : null;
    }

    public function getArrayValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::ARRAY]);

        return \is_array($value) ? $value : null;
    }

    /**
     * @return array<string>|null
     */
    public function getArrayStringValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::ARRAY_STRING]);
        if (\is_array($value)) {
            return \array_filter($value, static fn($v) => \is_string($v));
        }

        return null;
    }

    /**
     * @return array<int>|null
     */
    public function getArrayIntegerValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::ARRAY_INTEGER]);

        if (\is_array($value)) {
            return \array_filter($value, static fn($v) => \is_int($v));
        }

        return null;
    }

    /**
     * @return array<mixed>|null
     */
    public function getArrayUniqueValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::ARRAY_UNIQUE]);

        return \is_array($value) ? $value : null;
    }

    public function getJsonValue(mixed $default = null, bool $skipEmpty = true): ?string
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::JSON]);

        return \is_string($value) ? $value : null;
    }

    /**
     * @return array<int|string|float|bool>|null Unique string|int|float|bool values array or null
     */
    public function getListValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::LIST]);

        if (\is_array($value)) {
            return \array_filter($value, static fn($v) => \is_int($v) || \is_float($v) || \is_string($v) || \is_bool($v));
        }

        return null;
    }

    /**
     * @return string[]|null Unique string values array or null
     */
    public function getListStringValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::LIST_STRING]);

        if (\is_array($value)) {
            return \array_filter($value, static fn($v) => \is_string($v));
        }

        return null;
    }

    /**
     * @return int[]|null Unique integer values array or null
     */
    public function getListIntegerValue(mixed $default = null, bool $skipEmpty = true): ?array
    {
        /** @var mixed $value */
        $value = $this->getValue($default, $skipEmpty, [TypeCaster::LIST_INTEGER]);

        if (\is_array($value)) {
            return \array_filter($value, static fn($v) => \is_int($v));
        }

        return null;
    }
}
