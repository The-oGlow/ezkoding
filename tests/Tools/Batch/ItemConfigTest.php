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

use Ds\Map;
use ollily\Tools\Test\TestData as TeDa;
use PHPUnit\Framework\TestCase;

class ItemConfigTest extends TestCase
{
    private BatchConfig $o2t;

    #[\Override]
    protected function setUp(): void
    {
        $this->o2t = new BatchConfig(new Map());
    }

    public function testGetFullConfig(): void
    {
        $expected = new Map();
        $actual = $this->o2t->getFullConfig();

        self::assertEquals($expected, $actual);
    }

    public function testGetConfig(): void
    {
        $expectedKey = TeDa::KEY_ALPHA1;
        $expectedValue = TeDa::DATA_NULL;
        $actual = $this->o2t->getConfig($expectedKey);

        self::assertEquals($expectedValue, $actual);
    }

    public function testSetConfig(): void
    {
        $expectedKey = TeDa::KEY_ALPHA1;
        $expectedValueBefore = TeDa::DATA_NULL;
        $actual = $this->o2t->getConfig($expectedKey);
        self::assertEquals($expectedValueBefore, $actual);

        $expectedValueAfter = TeDa::DATA_ALPHA2;
        $this->o2t->setConfig($expectedKey, $expectedValueAfter);
        $actual = $this->o2t->getConfig($expectedKey);
        self::assertEquals($expectedValueAfter, $actual);
    }
}
