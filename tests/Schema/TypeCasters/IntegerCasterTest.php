<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests\TypeCasters;

use MXRVX\Schema\System\Settings\Tests\BaseTest;
use MXRVX\Schema\System\Settings\TypeCasters\IntegerCaster;

/** @covers \MXRVX\Schema\System\Settings\TypeCasters\IntegerCaster */
class IntegerCasterTest extends BaseTest
{
    public function getCorrectDataProvider(): array
    {
        return [
            [true, 1],
            [false, 0],
            [1, 1],
            [0, 0],
            [-999990, -999990],
            ['1', 1],
            ['0', 0],
            [1.11, 1],
            ['1.11', 1],
            ['-11.11', -11],
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
        $this->assertEquals($expected, IntegerCaster::cast($value));
    }

    public function getEmptyDataProvider(): array
    {
        return [
            [[], null],
            ['{}', null],
            ['[]', null],
            ['', null],
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
        $this->assertNull(IntegerCaster::cast($value));
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
        $this->assertNull(IntegerCaster::cast($value));
    }
}
