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

namespace ollily\Tools\Test;

use PHPUnit\Framework\TestCase;

class TestDataFooTest extends TestCase
{
    private TestDataFoo $o2t;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->o2t = TestDataFoo::init(TestData::KEY_ALPHA1);
    }

    public function testInit(): void
    {
        self::assertInstanceOf(TestDataFoo::class, $this->o2t);
    }

    public function testToString(): void
    {
        $expected = sprintf('%s:\'%s\'', get_class($this->o2t), TestData::KEY_ALPHA1);

        $actual = $this->o2t->__toString();

        self::assertEquals($expected, $actual);
    }
}
