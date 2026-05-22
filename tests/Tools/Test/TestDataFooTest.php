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
    /** @var TestDataFoo */
    private $o2t;

    protected function setUp(): void
    {
        parent::setUp();
        $this->o2t = TestDataFoo::init();
    }

    public function testInit(): void
    {
        self::assertInstanceOf(TestDataFoo::class, $this->o2t);
    }

    public function testToString(): void
    {
        $actual = $this->o2t->__toString();

        self::assertEmpty($actual);
    }
}
