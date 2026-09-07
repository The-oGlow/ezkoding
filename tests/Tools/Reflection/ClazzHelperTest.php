<?php

declare(strict_types=1);

/*
 * This file is part of ezkoding
 *
 * (c) 2025 Oliver Glowa, coding.glowa.com
 *
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ollily\Tools\Reflection;

use ollily\Tools\Test\TestData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ClazzHelperTest extends TestCase
{
    /**
     * @param string $expected
     * @param string $clazz
     *
     * @phpstan-param class-string $expected
     * @phpstan-param class-string $clazz
     */
    #[DataProvider('providerGetClazzFile')]
    public function testGetClazzFile(string $expected, string $clazz): void
    {
        $actual = ClazzHelper::getClazzFile($clazz);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param string $expected
     * @param string $clazz
     *
     * @phpstan-param class-string $expected
     * @phpstan-param class-string $clazz
     */
    #[DataProvider('providerGetClazzFile')]
    public function testGetClazzPath(string $expected, string $clazz): void
    {
        $expected = dirname($expected);

        $actual = ClazzHelper::getClazzPath($clazz);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param string $expected
     * @param string $clazz
     *
     * @phpstan-param class-string $expected
     * @phpstan-param class-string $clazz
     */
    #[DataProvider('providerGetClazzFile')]
    public function testGetClazzFilename(string $expected, string $clazz): void
    {
        $expected = basename($expected, TestData::FILE_EXT_PHP);

        $actual = ClazzHelper::getClazzFilename($clazz);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param int                $expected
     * @param string             $clazz
     * @param array<mixed,mixed> $childClazzes
     * @param bool               $isEqual
     *
     * @phpsta-param class-string $clazz
     */
    #[DataProvider('providerGetAllChildren')]
    public function testGetAllChildren(int $expected, string $clazz, array $childClazzes, bool $isEqual = true): void
    {
        $actual = ClazzHelper::getAllChildren($clazz);
        if ($isEqual) {
            self::assertCount($expected, $actual);
        } else {
            self::assertThat(count($actual), self::greaterThanOrEqual($expected));
        }
        self::assertNotContains($clazz, $actual);
        if (!empty($childClazzes)) {
            foreach ($childClazzes as $childClazz) {
                self::assertContains($childClazz, $actual);
            }
        }
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerGetClazzFile(): array
    {
        $expectedFile = new ReflectionClass(ClazzHelperTest::class)->getFileName();

        return [
            'noClass' => [TestData::DATA_EMPTY, TestData::DATA_EMPTY],
            'notExists' => [TestData::DATA_EMPTY, TestData::NOTEXIST_CLAZZ],
            'clazzExists' => [$expectedFile, ClazzHelperTest::class],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerGetAllChildren(): array
    {
        return [
            'noChildren' => [0, ClazzHelperTest::class, []],
            'clazzNotExists' => [0, TestData::NOTEXIST_CLAZZ, []],
            'oneOrManyChildren' => [4, TestCase::class, [self::class], false],
        ];
    }
}
