<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\TypeCaster;
use MXRVX\Schema\System\Settings\TypeCasterInterface;

/** @covers \MXRVX\Schema\System\Settings\TypeCaster */
class TypeCasterTest extends BaseTest
{
    public function testString(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::STRING,
        ];

        foreach (
            [
                ['  ffff', 'ffff'],
                ['  ', ''],
                [111, '111'],
                [-0.111, '-0.111'],
                [true, '1'],
                [false, '0'],
                [[2, 3, 4], '[2,3,4]'],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('string', \gettype($actual));
        }
    }

    public function testInteger(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::INTEGER,
        ];

        foreach (
            [
                [13233, 13233],
                ['111', 111],
                [-1.111, -1],
                [true, 1],
                [false, 0],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('integer', \gettype($actual));
        }
    }

    public function testFloat(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::FLOAT,
        ];

        foreach (
            [
                [-1.111, -1.111],
                [13233, 13233.0],
                ['111', 111.0],
                [true, 1.0],
                [false, 0.0],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('double', \gettype($actual));
        }
    }

    public function testBoolean(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::BOOLEAN,
        ];

        foreach (
            [
                [true, true],
                [false, false],
                ['true', true],
                [' false', false],
                ['TRUE', true],
                [' FALSE', false],
                [' 1 ', true],
                ['0', false],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('boolean', \gettype($actual));
        }
    }

    public function testDatetime(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::DATETIME,
        ];

        foreach (
            [
                ['2025-01-01 00:00:10', new \DateTime('2025-01-01 00:00:10')],
                ['2025-12-12', new \DateTime('2025-12-12')],
                [1745136010, new \DateTime('@1745136010')],
                ['1745136010', new \DateTime('@1745136010')],
                [new \DateTime('@1745136010'), new \DateTime('@1745136010')],
                ['-1745136010', new \DateTime('@-1745136010')],
                [-1745136010, new \DateTime('@-1745136010')],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertEquals($expected, $actual, \var_export($value, true));
            $this->assertEquals('object', \gettype($actual));
        }
    }

    public function testArray(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::ARRAY,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], [1, 2, 3]],
                [['1', 2, '3', null], ['1', 2, '3', null]],
                [\json_encode([]), []],
                [\json_encode([1, 2, 3]), [1, 2, 3]],
                [\json_encode(["ssds", "3232", "ывавыаыв"]), ["ssds", "3232", "ывавыаыв"]],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testArrayString(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::ARRAY_STRING,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], ['1', '2', '3']],
                [['1', 2, '3', null], ['1', '2', '3']],
                [\json_encode([1, 2, 3]), ['1', '2', '3']],
                [\json_encode(["1", "3232", "ывавыаыв"]), ["1", "3232", "ывавыаыв"]],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testArrayInteger(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::ARRAY_INTEGER,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], [1, 2, 3]],
                [['1', 2, '3', null], [1, 2, 3]],
                [\json_encode([1, 2, 3]), [1, 2, 3]],
                [\json_encode(["1", "3232", "ывавыаыв"]), [1, 3232, 0]],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testArrayUnique(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::ARRAY_UNIQUE,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], [1, 2, 3]],
                [['1', 2, '3', 3, null, null], ['1', 2, '3', null]],
                [\json_encode([1, 2, 3]), [1, 2, 3]],
                [\json_encode(["1", "3232", "ывавыаыв"]), ["1", "3232", "ывавыаыв"]],
                [[1, 2, 3, 3, 3], [1, 2, 3]],
                [\json_encode([1, 2, 3, 0, 0]), [1, 2, 3, 0]],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testJson(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::JSON,
        ];

        foreach (
            [
                [[], '[]'],
                [[1, 2, 3], \json_encode([1, 2, 3])],
                [['1', 2, '3', null], \json_encode(['1', 2, '3', null])],
                [\json_encode([1, 2, 3]), \json_encode([1, 2, 3])],
                [
                    \json_encode(["ssds", "3232", "ывавыаыв"], \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES),
                    \json_encode(["ssds", "3232", "ывавыаыв"], \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES),
                ],
                [["ssds", "3232", "ывавыаыв"], \json_encode(["ssds", "3232", "ывавыаыв"], \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES)],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertEquals($expected, $actual, \var_export($value, true));
            $this->assertEquals('string', \gettype($actual));
        }
    }

    public function testList(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::LIST,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], [1, 2, 3]],
                [['1', 2, '3', null], ['1', 2, '3']],
                [\json_encode([1, "2", 3]), [1, "2", 3]],
                [\json_encode(["ssds", "3232", "ывавыаыв"]), ["ssds", "3232", "ывавыаыв"]],
                ['1,2,  3,4,10,10,3', ['1', '2', '3', '4', '10', '10', '3']],
                ['1,2,  3,4,10,"10","3",null', ['1', '2', '3', '4', '10', '"10"', '"3"', 'null']],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testListString(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::LIST_STRING,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], ['1', '2', '3']],
                [['1', 2, '3', null], ['1', '2', '3']],
                [\json_encode([1, "2", 3]), ['1', '2', '3']],
                [\json_encode(["ssds", "3232", "ывавыаыв"]), ["ssds", "3232", "ывавыаыв"]],
                ['1,2,  3,4,10,10,3', ['1', '2', '3', '4', '10']],
                ['1,2,  3,4,10,"10","3",null', ['1', '2', '3', '4', '10', '"10"', '"3"', 'null']],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    public function testListInteger(): void
    {
        /** @var TypeCasterInterface[] $casters */
        $casters = [
            TypeCaster::LIST_INTEGER,
        ];

        foreach (
            [
                [[], []],
                [[1, 2, 3], [1, 2, 3]],
                [['1', 2, '3', null], [1, 2, 3]],
                [\json_encode([1, "2", 3]), [1, 2, 3]],
                [\json_encode(["ssds", "3232", "ывавыаыв"]), [3232]],
                ['1,2,  3,4,10,10,3', [1, 2, 3, 4, 10]],
                ['1,2,  3,4,10,"10","3",null', [1, 2, 3, 4, 10]],
            ] as $iteration
        ) {
            $value = $iteration[0];
            $expected = $iteration[1];

            $actual = $this->getValue($value, null, false, $casters);

            $this->assertNotNull($actual);
            $this->assertSame($expected, $actual, \var_export($value, true));
            $this->assertEquals('array', \gettype($actual));
        }
    }

    private function getTypeCasters(?array $typecaster = null): array
    {
        if ($casters = $typecaster ?? null) {
            $casters = \array_filter(
                $casters,
                static fn($class): bool => \is_string($class) && \is_a($class, TypeCasterInterface::class, true),
            );
        }

        return $casters ?? [];
    }

    private function shouldUseDefault(mixed $value, mixed $default, bool $skipEmpty): bool
    {
        return $skipEmpty
            && $value === null
            && $default !== null;
    }

    /**
     * @param  array<class-string<TypeCasterInterface>>  $casters
     *
     */
    private function applyTypeCasters(mixed $value, array $casters, bool $skipEmpty): mixed
    {
        /** @var TypeCasterInterface[] $casters */
        foreach ($casters as $caster) {
            /** @var mixed $value */
            $value = $caster::cast($value, $skipEmpty);

            if ($value === null && $skipEmpty) {
                break;
            }
        }

        return $value;
    }

    private function getValue(
        mixed $value,
        mixed $default = null,
        bool $skipEmpty = true,
        ?array $typecaster = null,
    ): mixed {
        $casters = $this->getTypeCasters($typecaster);
        if ($value !== null) {
            /** @var mixed $value */
            $value = $this->applyTypeCasters($value, $casters, $skipEmpty);
        }

        if ($this->shouldUseDefault($value, $default, $skipEmpty)) {
            /** @var mixed $value */
            $value = $this->applyTypeCasters($default, $casters, false);
        }

        return $value;
    }
}
