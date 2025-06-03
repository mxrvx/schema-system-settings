<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

interface SchemaConfigInterface
{
    public function getNamespace(): string;

    public function getSchema(): Schema;

    public function getSettings(): Settings;

    public function getSetting(string $key): ?Setting;

    public function getSettingsArray(): array;

    public function setSettingValue(string $key, mixed $value): void;

    public function getSettingValue(string $key, mixed $default = null, bool $skipEmpty = true, ?array $typecaster = null): mixed;
}
