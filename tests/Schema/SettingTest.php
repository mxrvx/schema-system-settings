<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\Setting;
use MXRVX\Schema\System\Settings\TypeCaster;

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

    public function testGetStringValue(): void
    {
        foreach (
            [
                [null, null],
                [0, '0'],
                [1, '1'],
                [0.111, '0.111'],
                [true, '1'],
                [false, '0'],
                [[], ''],
                [[1, 2, 3], '1,2,3'],
                ['[]', '[]'],
                ['1,2,3', '1,2,3'],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getStringValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetIntegerValue(): void
    {
        foreach (
            [
                [null, 0],
                [0, 0],
                [1, 1],
                [0.111, 0],
                [true, 1],
                [false, 0],
                [[], 0],
                [[1, 2, 3], 0],
                ['[]', 0],
                ['1,2,3', 0],
                ['123', 123],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getIntegerValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetFloatValue(): void
    {
        foreach (
            [
                [null, 0.0],
                [0, 0.0],
                [1, 1.0],
                [0.111, 0.111],
                [true, 1.0],
                [false, 0.0],
                [[], 0.0],
                [[1, 2, 3], 0.0],
                ['[]', 0.0],
                ['1,2,3', 0.0],
                ['123.45', 123.45],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getFloatValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetBoolValue(): void
    {
        foreach (
            [
                [null, null],
                [0, false],
                [1, true],
                [0.0, false],
                [0.111, null],
                [true, true],
                [false, false],
                [[], null],
                [[1, 2, 3], null],
                ['[]', null],
                ['0', false],
                ['1', true],
                ['', null],
                ['any string', null],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getBoolValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetDateTimeValue(): void
    {
        $tz = new \DateTimeZone('Europe/Moscow');

        foreach (
            [
                [null, null],
                ['', null],
                ['invalid date', null],
                ['2025-03-02', new \DateTimeImmutable('2025-03-02 00:00:00', $tz)],
                ['2025-06-15 10:43:00', new \DateTimeImmutable('2025-06-15 10:43:00', $tz)],
                ['15 June 2025', new \DateTimeImmutable('15 June 2025', $tz)],
                ['2025-13-01', null], // неверный месяц
                ['2025-02-33', null], // неверный день
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getDateTimeValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertInstanceOf(\DateTimeInterface::class, $realValue);
                $this->assertEquals(
                    $expected->format('Y-m-d H:i:s'),
                    $realValue->format('Y-m-d H:i:s'),
                );
                $this->assertEquals(
                    $expected->getTimezone()->getName(),
                    $realValue->getTimezone()->getName(),
                );
            }
        }
    }

    public function testGetArrayValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', ['not an array']],
                [[], null],
                [[1, 2, 3], [1, 2, 3]],
                [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2]],
                ['["json", "array"]', ["json", "array"]],
                [[null, false, true], [null, false, true]],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getArrayValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetArrayStringValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', ['not an array']],
                [[], null],
                [['a', 'b', 'c'], ['a', 'b', 'c']],
                [[1, 2, 3], ['1', '2', '3']],
                [['a', 2, true], ['a', '2', '1']],
                [['', null, false], ['', '']],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getArrayStringValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);

                foreach ($realValue as $item) {
                    $this->assertIsString($item);
                }
            }
        }
    }

    public function testGetArrayIntegerValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', [0]],
                [[], null],
                [['1', '2', '3'], [1, 2, 3]],
                [[1, 2, 3], [1, 2, 3]],
                [['a', 2, true], [0, 2, 1]],
                [['', null, false], [0, 0]],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getArrayIntegerValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
                foreach ($realValue as $item) {
                    $this->assertIsInt($item);
                }
            }
        }
    }

    public function testGetArrayUniqueValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', ['not an array']],
                [[], null],
                [[1, 2, 2, 3, 3, 3], [1, 2, 3]],
                [['a', 'b', 'a', 'c', 'b'], ['a', 'b', 'c']],
                [[1, '1', 2, '2', 2], [1, 2]],
                [[null, null, false, false], [false]],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getArrayUniqueValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetJsonValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['invalid json', null],
                ['{}', null],
                ['[]', null],
                ['{"a":1,"b":2}', '{"a":1,"b":2}'],
                ['[1,2,3]', '[1,2,3]'],
                ['true', null],
                ['false', null],
                ['null', null],
                ['"string"', null],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getJsonValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertEquals($expected, $realValue);
            }
        }
    }

    public function testGetListValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', ['not an array']],
                [[], null],
                [[1, 2, 2, 3], [1, 2, 2, 3]],
                [['a', 'b', 'a', 'c'], ['a', 'b', 'a', 'c']],
                [[1, '1', 2, '2', 2], [1, '1', 2, '2', 2]],
                [[true, false, true], [true, false, true]],
                [[1.1, 2.2, 1.1], [1.1, 2.2, 1.1]],
                [['', null, false], [false]],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getListValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
                foreach ($realValue as $item) {
                    $this->assertTrue(
                        \is_int($item) || \is_string($item) || \is_float($item) || \is_bool($item),
                        'Item type is not int|string|float|bool',
                    );
                }
            }
        }
    }

    public function testGetListStringValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', ['not an array']],
                [[], null],
                [['apple', 'banana', 'apple'], ['apple', 'banana']],
                [[1, 2, 3], ['1', '2', '3']],
                [['a', 2, true], ['a', '2', '1']],
                [['', null, false], ['0']],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getListStringValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
                foreach ($realValue as $item) {
                    $this->assertIsString($item);
                }
            }
        }
    }

    public function testGetListIntegerValue(): void
    {
        foreach (
            [
                [null, null],
                ['', null],
                ['not an array', null],
                [[], null],
                [[1, 2, 2, 3], [1, 2, 3]],
                [['1', '2', '2', '3'], [1, 2, 3]],
                [[1.1, 2.5, 2.5], [1, 2]],
                [[true, false, true], [1, 0]],
                [['a', 2, true], [2, 1]],
            ] as [$input, $expected]
        ) {
            $setting = Setting::define(
                key: 'key1',
                value: null,
                xtype: 'textfield',
            );

            $setting->setValue($input);
            $realValue = $setting->getListIntegerValue();

            if ($expected === null) {
                $this->assertNull($realValue);
            } else {
                $this->assertIsArray($realValue);
                $this->assertEquals($expected, $realValue);
                foreach ($realValue as $item) {
                    $this->assertIsInt($item);
                }
            }
        }
    }
}
