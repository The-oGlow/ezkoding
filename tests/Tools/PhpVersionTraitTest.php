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

namespace ollily\Tools;

use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;

class PhpVersionTraitTest extends TestCase
{
    use PhpVersionTrait;

    public const PHP_VERSION_MIN = '0.0.1';

    public const PHP_VERSION_CURR = PHP_VERSION;

    public const PHP_VERSION_MAX = '99.99.999';

    /**
     * @param bool   $expected
     * @param string $checkVersion
     *
     * @dataProvider providerPhpVersion
     */
    public function testIsPhpGreater(bool $expected, string $checkVersion): void
    {
        $actual = $this->isPhpGreater($checkVersion);
        self::assertEquals($expected, $actual);
    }

    // Dataprovider

    /**
     * @return array<mixed,mixed>
     */
    public function providerPhpVersion(): array
    {
        return [
            'equal' => [true, self::PHP_VERSION_CURR],
            'lower' => [true, self::PHP_VERSION_MIN],
            'higher' => [false, self::PHP_VERSION_MAX],
            'wrong' => [true, TestData::DATA_INVALID],
        ];
    }
}
