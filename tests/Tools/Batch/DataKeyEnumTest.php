<?php

/*
 * Copyright 2026 postm.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
