<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

class Schema implements SchemaInterface
{
    /** @var array<array-key,mixed> */
    private array $config = [];

    /**
     * @throws \Exception
     */
    private function __construct(private readonly string $namespace, private Settings $settings)
    {
        if (empty($namespace)) {
            throw new \Exception('`namespace` is required.');
        }
    }

    public static function define(string $namespace): Schema
    {
        return new self($namespace, new Settings());
    }

    /**
     * @throws \Exception
     */
    public function withSetting(Setting $setting): self
    {
        $key = $setting->getKey();
        if (!$this->settings->has($key)) {
            $this->validateSetting($setting);
            $this->settings->set($key, $setting);
        }

        return $this;
    }

    /**
     * @param  array<array-key,Setting>  $settings
     *
     * @return $this
     * @throws \Exception
     */
    public function withSettings(array $settings): self
    {
        $this->settings = new Settings();

        foreach ($settings as $setting) {
            $this->validateSetting($setting);
            $key = $setting->getKey();
            $this->settings->set($key, $setting);
        }

        return $this;
    }

    /**
     * @param  array<array-key,mixed>  $config
     *
     * @return $this
     * @throws \Exception
     */
    public function withConfig(array &$config): self
    {
        $this->config = &$config;
        foreach ($this->settings as $setting) {
            $settingKey = $setting->getKey($this->namespace);
            if (isset($config[$settingKey])) {
                $setting->setValue($config[$settingKey]);
            } else {
                $setting->setValue();
            }
        }
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getSettings(): Settings
    {
        return $this->settings;
    }

    public function getSettingsArray(): array
    {
        $array = [];
        foreach ($this->settings as $key => $setting) {
            $array[$key] = $setting->toArray($this->namespace, true, true);
        }
        return $array;
    }

    public function getSetting(string $key): ?Setting
    {
        return $this->settings->get($key);
    }

    public function getSettingBySettingKey(string $settingKey): ?Setting
    {
        foreach ($this->settings as $setting) {
            if ($setting->getKey($this->namespace) === $settingKey) {
                return $setting;
            }
        }

        return null;
    }

    /**
     * @param  null|array<class-string<TypeCasterInterface>>  $typecaster
     *
     */
    public function getSettingValue(string $key, mixed $default = null, bool $skipEmpty = true, ?array $typecaster = null): mixed
    {
        return $this->settings->get($key)?->getValue($default, $skipEmpty, $typecaster);
    }

    public function setSettingValue(string $key, mixed $value): void
    {
        if ($setting = $this->settings->get($key)) {
            $settingKey = $setting->getKey($this->namespace);
            $this->config[$settingKey] = $value;
        }
    }

    /**
     *
     * @throws \Exception
     */
    private function validateSetting(Setting $setting): void
    {
        $settingKey = $setting->getKey($this->namespace);
        if (\mb_strlen($settingKey) > 50) {
            throw new \Exception(\sprintf('Setting key `%s` is too long', $settingKey));
        }
    }
}
