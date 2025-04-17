<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests\TypeCasters;

use MXRVX\Schema\System\Settings\Tests\BaseTest;
use MXRVX\Schema\System\Settings\TypeCasters\DateTimeCaster;

/** @covers \MXRVX\Schema\System\Settings\TypeCasters\DateTimeCaster */
class DateTimeCasterTest extends BaseTest
{
    public function getCorrectDataProvider(): array
    {
        return [
            ['2025-01-01 00:00:10',  new \DateTime('2025-01-01 00:00:10')],
            ['2025-12-12',  new \DateTime('2025-12-12')],
            [1745136010,  new \DateTime('@1745136010')],
            ['1745136010',  new \DateTime('@1745136010')],
            [ new \DateTime('@1745136010'),  new \DateTime('@1745136010')],
            ['-1745136010',  new \DateTime('@-1745136010')],
            [-1745136010,  new \DateTime('@-1745136010')],
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
        $this->assertEquals($expected, DateTimeCaster::cast($value));
    }

    public function getEmptyDataProvider(): array
    {
        return [
            [[], null],
            ['{}', null],
            ['[]', null],
            ['', null],
            [0.1, null],
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
        $this->assertNull(DateTimeCaster::cast($value));
    }

    public function getNotCorrectDataProvider(): array
    {
        return [
            [new \stdClass(), null],
            ['', null],
            ['{"sdds: 33}', null],
            ['[1,"3,4]', null],
            ['-91111111121', null],
            [-91111111121, null],
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
        $this->assertNull(DateTimeCaster::cast($value));
    }
}
