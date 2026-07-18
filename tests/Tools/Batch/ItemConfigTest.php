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

use Ds\Map;
use PHPUnit\Framework\TestCase;
use ollily\Tools\Test\TestData;

class ItemConfigTest extends TestCase
{

    private ItemConfig $o2t;

    #[\Override]
    protected function setUp(): void
    {
        $this->o2t = new ItemConfig(new Map());
    }

    public function testGetFullConfig(): void
    {
        $expected = new Map();
        $actual = $this->o2t->getFullConfig();

        self::assertEquals($expected, $actual);
    }

    public function testGetConfig(): void
    {
        $expectedKey = TestData::KEY_ALPHA1;
        $expectedValue = TestData::DATA_NULL;
        $actual = $this->o2t->getConfig($expectedKey);

        self::assertEquals($expectedValue, $actual);
    }

    public function testSetConfig(): void
    {
        $expectedKey = TestData::KEY_ALPHA1;
        $expectedValueBefore = TestData::DATA_NULL;
        $actual = $this->o2t->getConfig($expectedKey);
        self::assertEquals($expectedValueBefore, $actual);

        $expectedValueAfter = TestData::DATA_ALPHA2;
        $this->o2t->setConfig($expectedKey, $expectedValueAfter);
        $actual = $this->o2t->getConfig($expectedKey);
        self::assertEquals($expectedValueAfter, $actual);
    }
}
