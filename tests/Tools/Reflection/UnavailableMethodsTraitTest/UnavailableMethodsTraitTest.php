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

namespace ollily\Tools\Reflection\UnavailableMethodsTraitTest;

use PHPUnit\Framework\TestCase;

/**
 * This is the test clazz which will test the test clazz.
 *
 * @see UnavailableMethodsTraitTestO2tClazz
 * @see UnavailableMethodsTraitTestWrongO2tClazz
 */
class UnavailableMethodsTraitTest extends TestCase
{
    /** @var UnavailableMethodsTraitTestO2tClazz */
    protected UnavailableMethodsTraitTestO2tClazz $o2t;

    /** @var string[] */
    private array $methodNames = ['publicFunc', 'protectedFunc', 'privateFunc'];

    public function setUp(): void
    {
        parent::setUp();
        $this->o2t = new UnavailableMethodsTraitTestO2tClazz();
    }

    public function testCallMethodByReflection(): void
    {
        foreach ($this->methodNames as $methodName) {
            self::assertEquals($methodName . 'Value', $this->o2t->publicCallMethodByReflection($methodName));
        }
    }

    public function testCallMethodOnO2t(): void
    {
        foreach ($this->methodNames as $methodName) {
            self::assertEquals($methodName . 'Value', $this->o2t->publicCallMethodOnO2t($methodName));
        }
    }

    public function testCallMethodOnO2tReturnNull(): void
    {
        /** @var UnavailableMethodsTraitTestWrongO2tClazz $o2tb */
        $o2tb = new UnavailableMethodsTraitTestWrongO2tClazz();
        foreach ($this->methodNames as $methodName) {
            self::assertNull($o2tb->publicCallMethodOnO2t($methodName));
        }
    }
}
