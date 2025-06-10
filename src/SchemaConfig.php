<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

class SchemaConfig implements SchemaConfigInterface
{
    private function __construct(private readonly Schema $schema) {}

    public static function define(Schema $schema): SchemaConfig
    {
        return new self($schema);
    }

    public function getNamespace(): string
    {
        return $this->schema->getNamespace();
    }

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * @param  array<array-key,mixed>  $config
     *
     * @throws \Exception
     */
    public function withConfig(array &$config): SchemaConfig
    {
        $this->schema->withConfig($config);
        return $this;
    }

    public function getSettings(): Settings
    {
        return $this->schema->getSettings();
    }

    public function getSettingsByArea(string $area): array
    {
        return $this->schema->getSettingsByArea($area);
    }

    public function getSettingsArray(): array
    {
        return $this->schema->getSettingsArray();
    }

    public function getSetting(string $key): ?Setting
    {
        return $this->schema->getSetting($key);
    }

    public function getSettingBySettingKey(string $settingKey): ?Setting
    {
        return $this->schema->getSettingBySettingKey($settingKey);
    }

    public function getSettingKey(string $key, bool $withNamespace = true): ?string
    {
        return $this->schema->getSetting($key)?->getKey($withNamespace ? $this->getNamespace() : '');
    }

    /**
     * @param  null|array<class-string<TypeCasterInterface>>  $typecaster
     *
     */
    public function getSettingValue(string $key, mixed $default = null, bool $skipEmpty = true, ?array $typecaster = null): mixed
    {
        return $this->schema->getSettingValue($key, $default, $skipEmpty, $typecaster);
    }

    public function setSettingValue(string $key, mixed $value): void
    {
        $this->schema->setSettingValue($key, $value);
    }
}
