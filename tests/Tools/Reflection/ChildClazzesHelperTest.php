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

class ChildClazzesHelperTest extends TestCase
{
    /**
     * @param array<mixed,mixed> $childClazzes
     */
    #[DataProvider('providerChildClazzes')]
    public function testAllChildren(int $expected, string $clazzName, array $childClazzes, bool $isEqual = true): void
    {
        $actual = ChildClazzesHelper::getAllChildren($clazzName);
        if ($isEqual) {
            self::assertCount($expected, $actual);
        } else {
            self::assertThat(count($actual), self::greaterThanOrEqual($expected));
        }
        self::assertNotContains($clazzName, $actual);
        if (count($childClazzes) > 0) {
            foreach ($childClazzes as $childClazz) {
                self::assertContains($childClazz, $actual);
            }
        }
    }

    /**
     * @return array<mixed,mixed>
     */
    public static function providerChildClazzes(): array
    {
        return [
            'noChildren' => [0, ChildClazzesHelperTest::class, []],
            'clazzNotExists' => [0, TestData::NOTEXIST_CLAZZ, []],
            'oneOrManyChildren' => [17, TestCase::class, [self::class], false],
        ];
    }
}
