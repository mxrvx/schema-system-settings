<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\Schema;
use MXRVX\Schema\System\Settings\Setting;
use MXRVX\Schema\System\Settings\Settings;

/** @covers \MXRVX\Schema\System\Settings\Schema */
class SchemaTest extends BaseTest
{
    public function testDefine(): void
    {
        $this->assertInstanceOf(Schema::class, $this->schema);
    }

    public function testSettings(): void
    {
        $this->assertInstanceOf(Settings::class, $this->settings);
    }

    public function testWithSetting(): void
    {
        $this->schema
            ->withSetting(
                Setting::define(
                    key: 'key1',
                    value: 1,
                    xtype: 'textfield',
                ),
            );

        $this->assertInstanceOf(Setting::class, $this->schema->getSetting('key1'));
    }

    public function testWithSettings(): void
    {
        $this->schema
            ->withSettings(
                [
                    Setting::define(
                        key: 'key1',
                        value: 1,
                        xtype: 'textfield',
                    ),
                    Setting::define(
                        key: 'key2',
                        value: '2',
                        xtype: 'textfield',
                    ),
                ],
            );

        $this->assertEquals(2, $this->schema->getSettings()->count());
    }

    public function testWithConfig(): void
    {
        $settings = [];
        foreach ($this->getTestingSettings() as $key => $value) {
            $settings[] = Setting::define(
                key: $key,
                value: null,
                xtype: 'textfield',
            );
        }

        $this->schema->withSettings($settings);

        $config = $this->getTestingSettings(true);
        $this->schema->withConfig($config);

        foreach ($this->getTestingSettings() as $key => $value) {
            $this->assertEquals($value, $this->schema->getSetting($key)?->getValue());
        }
    }

    public function getNamespace(): void
    {
        $this->assertEquals($this->namespace, $this->schema->getNamespace());
    }

    public function testGetSettings(): void
    {
        $this->assertInstanceOf(Settings::class, $this->schema->getSettings());
    }

    public function testGetSetting(): void
    {
        $this->schema
            ->withSettings(
                [
                    Setting::define(
                        key: 'key1',
                        value: 1,
                        xtype: 'textfield',
                    ),
                    Setting::define(
                        key: 'key2',
                        value: '2',
                        xtype: 'textfield',
                    ),
                ],
            );

        $this->assertInstanceOf(Setting::class, $this->schema->getSetting('key1'));
        $this->assertInstanceOf(Setting::class, $this->schema->getSetting('key2'));
        $this->assertNull($this->schema->getSetting('key_not_exist'));
    }

    public function testGetSettingsArray(): void
    {
        $settings = [];
        foreach ($this->getTestingSettings() as $key => $value) {
            $settings[] = Setting::define(
                key: $key,
                value: $value,
                xtype: 'textfield',
            );
        }

        $this->schema->withSettings($settings);

        $array = $this->schema->getSettingsArray();

        $this->assertEquals(\count($this->getTestingSettings()), \count($array));
    }

    public function testGetSettingValue(): void
    {
        $settings = [];
        foreach ($this->getTestingSettings() as $key => $value) {
            $settings[] = Setting::define(
                key: $key,
                value: $value,
                xtype: 'textfield',
            );
        }

        $this->schema->withSettings($settings);

        $config = $this->getTestingSettings(true);
        $this->schema->withConfig($config);

        foreach ($this->getTestingSettings() as $key => $value) {
            $this->assertEquals($value, $this->schema->getSetting($key)?->getValue());
        }
    }

    public function testGetKeyException(): void
    {
        $this->expectException(\Exception::class);

        $this->schema
            ->withSetting(
                Setting::define(
                    key: 'key_is_very_long_name_key_is_very_long_name',
                    value: 1,
                    xtype: 'textfield',
                ),
            );
    }
}
