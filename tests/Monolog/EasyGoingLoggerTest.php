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

namespace Monolog;

use PHPUnit\Framework\TestCase;

class EasyGoingLoggerTest extends TestCase
{
    public function testInit(): void
    {
        /** @psalm-var class-string */
        $expected = 'Psr\Log\NullLogger';

        $actual = EasyGoingLogger::init(self::class);

        self::assertInstanceOf($expected, $actual);
    }
}
