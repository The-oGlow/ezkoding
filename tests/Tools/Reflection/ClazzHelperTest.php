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

use ollily\Tools\Test\TestData as TeDa;
use PHPUnit\Framework\TestCase;

class ClazzHelperTest extends TestCase
{
    /**
     * @param string $expected
     * @param string $clazzName
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerGetClazzFile')]
    public function testGetClazzFile(string $expected, string $clazzName): void
    {
        $actual = ClazzHelper::getClazzFile($clazzName);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param string $expected
     * @param string $clazzName
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerGetClazzFile')]
    public function testGetClazzPath(string $expected, string $clazzName): void
    {
        $expected = dirname($expected);

        $actual = ClazzHelper::getClazzPath($clazzName);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param string $expected
     * @param string $clazzName
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerGetClazzFile')]
    public function testGetClazzFilename(string $expected, string $clazzName): void
    {
        $expected = basename($expected, TeDa::FILE_EXT_PHP);

        $actual = ClazzHelper::getClazzFilename($clazzName);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param int          $expected
     * @param string       $clazzName
     * @param array<mixed> $childClazzes
     * @param bool         $isEqual
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providerGetAllChildren')]
    public function testGetAllChildren(int $expected, string $clazzName, array $childClazzes, bool $isEqual = true): void
    {
        $actual = ClazzHelper::getAllChildren($clazzName);
        if ($isEqual) {
            self::assertCount($expected, $actual);
        } else {
            self::assertThat(count($actual), self::greaterThanOrEqual($expected));
        }
        self::assertNotContains($clazzName, $actual);
        if (!empty($childClazzes)) {
            foreach ($childClazzes as $childClazz) {
                self::assertContains($childClazz, $actual);
            }
        }
    }

    /**
     * @return array<mixed>
     */
    public static function providerGetClazzFile(): array
    {
        $expectedClazz = new \ReflectionClass(ClazzHelperTest::class);
        $expectedFile = $expectedClazz->getFileName();

        return [
            'noClass' => [TeDa::DATA_EMPTY, TeDa::DATA_EMPTY],
            'notExists' => [TeDa::DATA_EMPTY, TeDa::NOTEXIST_CLAZZ],
            'clazzExists' => [$expectedFile, ClazzHelperTest::class],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function providerGetAllChildren(): array
    {
        return [
            'noChildren' => [0, ClazzHelperTest::class, []],
            'clazzNotExists' => [0, TeDa::NOTEXIST_CLAZZ, []],
            'oneOrManyChildren' => [4, TestCase::class, [self::class], false],
        ];
    }
}
