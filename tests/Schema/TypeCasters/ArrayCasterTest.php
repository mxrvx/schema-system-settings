<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests\TypeCasters;

use MXRVX\Schema\System\Settings\Tests\BaseTest;
use MXRVX\Schema\System\Settings\TypeCasters\ArrayCaster;

/** @covers \MXRVX\Schema\System\Settings\TypeCasters\ArrayCaster */
class ArrayCasterTest extends BaseTest
{
    public function getCorrectDataProvider(): array
    {
        return [
            [[1, 2, 3], [1, 2, 3]],
            [['1', 2, '3', null], ['1', 2, '3', null]],
            [\json_encode([1, 2, 3]), [1, 2, 3]],
            [\json_encode(["ssds", "3232", "ывавыаыв"]), ["ssds", "3232", "ывавыаыв"]],
            ['1,2,55', [1,2,55]],
        ];
    }

    /**
     * @dataProvider getCorrectDataProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function testTransformCorrect($value, $expected): void
    {
        $this->assertNotNull($value);
        $this->assertEquals($expected, ArrayCaster::cast($value));
    }

    public function getEmptyDataProvider(): array
    {
        return [
            [[], null],
            ['{}', null],
            ['[]', null],
            ['', null],
            [true, null],
            [false, null],
        ];
    }

    /**
     * @dataProvider getEmptyDataProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function testTransformEmpty($value, $expected): void
    {
        $this->assertNotNull($value);
        $this->assertNull($expected);
        $this->assertNull(ArrayCaster::cast($value));
    }

    public function getNotCorrectDataProvider(): array
    {
        return [
            [new \stdClass(), null],
            ['', null],
            ['{"sdds: 33}', null],
            ['[1,"3,4]', null],
        ];
    }

    /**
     * @dataProvider getNotCorrectDataProvider
     *
     * @param  mixed  $value
     * @param  mixed  $expected
     */
    public function testTransformNotCorrect($value, $expected): void
    {
        $this->assertNotNull($value);
        $this->assertNull($expected);
        $this->assertNull(ArrayCaster::cast($value));
    }
}
