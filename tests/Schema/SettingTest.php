<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\TypeCaster;
use MXRVX\Schema\System\Settings\Setting;

/** @covers \MXRVX\Schema\System\Settings\Setting */
class SettingTest extends BaseTest
{
    public function testDefine(): void
    {
        $setting = Setting::define(
            key: 'key1',
            value: 1,
            xtype: 'textfield',
        );

        $this->assertInstanceOf(Setting::class, $setting);
    }

    public function testWithValue(): void
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

        foreach ($this->getTestingSettings() as $key => $value) {
            $this->assertEquals($value, $this->schema->getSetting($key)?->getValue());
        }
    }

    public function testGetTypeCasters(): void
    {
        foreach (
            [
                [],
                [TypeCaster::STRING],
                [TypeCaster::STRING, TypeCaster::ARRAY],
            ] as $typecast
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: 1,
                xtype: 'textfield',
                typecast: $typecast,
            );

            $this->assertEquals($typecast, $setting->getTypeCasters());
        }
    }

    public function testGetKey(): void
    {
        $this->schema
            ->withSetting(
                Setting::define(
                    key: 'key1',
                    value: 1,
                    xtype: 'textfield',
                ),
            );

        $setting = $this->schema->getSetting('key1');

        $this->assertEquals('key1', $setting->getKey());
        $this->assertEquals($this->namespace . '.key1', $setting->getKey($this->namespace));
    }

    public function testGetValue(): void
    {
        // NOTE getValue() with empty typecast
        foreach (
            [
                null,
                0,
                1,
                0.111,
                true,
                false,
                [],
                [1, 2, 3],
                '[]',
                '1,2,3',
            ] as $value
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: $value,
                xtype: 'textfield',
            );

            $this->assertEquals($value, $setting->getValue());
        }
    }

    public function testSetValue(): void
    {
        // NOTE setValue() with empty typecast
        foreach (
            [
                null,
                0,
                1,
                0.111,
                true,
                false,
                [],
                [1, 2, 3],
                '[]',
                '1,2,3',
            ] as $value
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: 'default',
                xtype: 'textfield',
            );

            $setting->setValue($value);

            $this->assertEquals($value, $setting->getValue(null, false));
        }
    }

    public function testToArray(): void
    {
        $settings = [
            [
                'key' => 'key1',
                'value' => null,
                'xtype' => 'textfield',
                'area' => '',
                'typecast' => null,
            ],
            [
                'key' => 'key1',
                'value' => null,
                'xtype' => 'textfield',
                'area' => '',
                'typecast' => [TypeCaster::STRING],
            ],
        ];

        foreach ($settings as $row) {
            $setting = Setting::define(...$row);

            $this->assertSame($row, $setting->toArray());
        }
    }
}
