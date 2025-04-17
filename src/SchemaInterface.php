<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

interface SchemaInterface
{
    public function getNamespace(): string;

    public function getSettings(): Settings;

    /**
     * Get settings array
     *
     * @return array<array-key,array<array-key,mixed>>
     */
    public function getSettingsArray(): array;

    public function getSetting(string $key): ?Setting;

    public function getSettingBySettingKey(string $settingKey): ?Setting;

    /**
     * @param  null|array<class-string<TypeCasterInterface>>  $typecaster
     *
     */
    public function getSettingValue(string $key, mixed $default = null, bool $skipEmpty = true, ?array $typecaster = null): mixed;

    public function setSettingValue(string $key, mixed $value): void;
}
