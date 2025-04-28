<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

final class Setting
{
    private function __construct(
        private readonly string $key,
        private readonly mixed $initial,
        private mixed &$value,
        private readonly string $xtype,
        private readonly string $area,
        private readonly null|string|array $typecast,
    ) {}

    /**
     *
     * @return static
     */
    public static function define(string $key, mixed $value, string $xtype, string $area = '', null|string|array $typecast = null): self
    {
        \assert(!empty($key));
        \assert(!empty($xtype));
        return new self(key: $key, initial: $value, value: $value, xtype: $xtype, area: $area, typecast: $typecast);
    }

    /**
     *
     * @return array<class-string<TypeCasterInterface>>
     */
    public function getTypeCasters(null|string|array $typecast = null): array
    {
        $typecast = $typecast ?? $this->typecast;

        $casters = match (true) {
            \is_string($typecast) => [$typecast],
            \is_array($typecast) => $typecast,
            default => [],
        };

        return \array_filter(
            $casters,
            static fn($class): bool => \is_string($class) && \class_exists($class) && \is_a($class, TypeCasterInterface::class, true),
        );
    }

    public function getKey(?string $namespace = null): string
    {
        return $namespace === null ? $this->key : $namespace . '.' . $this->key;
    }

    public function setValue(mixed &$value = null): void
    {
        $this->value = &$value;
    }

    /**
     * @param  null|array<class-string<TypeCasterInterface>>  $typecaster
     *
     */
    public function getValue(mixed $default = null, bool $skipEmpty = true, ?array $typecaster = null): mixed
    {
        $casters = $this->getTypeCasters($typecaster);

        /** @var mixed $value */
        $value = $this->value;

        if ($value !== null) {
            /** @var mixed $value */
            $value = $this->applyTypeCasters($value, $casters, $skipEmpty);
        }

        if ($this->shouldUseDefault($value, $default, $skipEmpty)) {
            /** @var mixed $value */
            $value = $this->applyTypeCasters($default, $casters, false);
        }

        if ($this->shouldUseDefault($value, $this->initial, $skipEmpty)) {
            /** @var mixed $value */
            $value = $this->applyTypeCasters($this->initial, $casters, false);
        }

        return $value;
    }

    public function toArray(?string $namespace = null, bool $processValue = false): array
    {
        return [
            'key' => $this->getKey($namespace),
            'value' => $processValue ? $this->getValue() : $this->value,
            'xtype' => $this->xtype,
            'namespace' => $namespace ?? '',
            'area' => $this->area,
            'typecast' => $this->typecast,
        ];
    }

    /**
     * @param  array<class-string<TypeCasterInterface>>  $casters
     *
     */
    private function applyTypeCasters(mixed $value, array $casters, bool $skipEmpty): mixed
    {
        /** @var TypeCasterInterface[] $casters */
        foreach ($casters as $caster) {
            /** @var mixed $value */
            $value = $caster::cast($value, $skipEmpty);

            if ($value === null && $skipEmpty) {
                break;
            }
        }

        return $value;
    }

    private function shouldUseDefault(mixed $value, mixed $default, bool $skipEmpty): bool
    {
        return $skipEmpty
            && $value === null
            && $default !== null;
    }
}
