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

namespace ollily\Tools\Batch;

use PHPUnit\Framework\TestCase;

class DataKeyEnumTest extends TestCase
{
    public function testName(): void
    {
        foreach (DataKeyEnum::cases() as $key) {
            $actual = $key->name;
            self::assertGreaterThan(0, strlen($actual));
            if (DataKeyEnum::PAGE_ID == $key) {
                $expected = 'PAGE_ID';
                self::assertEquals($expected, $actual);
            }
        }
    }

    public function testValue(): void
    {
        foreach (DataKeyEnum::cases() as $key) {
            $actual = $key->value;
            self::assertGreaterThan(0, strlen($actual));
            if (DataKeyEnum::PAGE_ID == $key) {
                $expected = 'Page Id';
                self::assertEquals($expected, $actual);
            }
        }
    }

    public function testText(): void
    {
        foreach (DataKeyEnum::cases() as $key) {
            $actual = $key->text();
            self::assertGreaterThan(0, strlen($actual));
            if (DataKeyEnum::PAGE_ID == $key) {
                $expected = 'Page ID';
                self::assertEquals($expected, $actual);
            }
        }
    }

    public function testIntValue(): void
    {
        $expected = 0;
        foreach (DataKeyEnum::cases() as $key) {
            $actual = $key->intValue();
            self::assertEquals($expected, $actual);
        }
    }
}
