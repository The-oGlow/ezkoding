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

class EmergencyTest extends TestCase
{
    public const CODE_NEG1     = -1;

    public const CODE_0        = 0;

    public const CODE_89       = 89;

    public const CODE_123      = 123;

    public const CODE_255      = 255;

    public const MSG_EMPTY = '';

    public const MSG_01    = 'ERR-MSG';

    public const MSG_02    = 'There is something worried';

    /**
     * @param int    $errCode
     * @param string $errMessage
     * @param int    $expected
     *
     * @dataProvider providerBreakSystem
     */
    public function testBreakSystem(int $errCode, string $errMessage, int $expected): void
    {
        $actual = Emergency::breakSystem($errCode, $errMessage, true);

        self::assertEquals($expected, $actual);
    }

    /**
     * @param \Throwable $throwable
     * @param int        $expected
     *
     * @dataProvider providerExceptionStop
     */
    public function testExceptionStop(\Throwable $throwable, int $expected): void
    {
        $actual = Emergency::exceptionStop($throwable, true);

        self::assertEquals($expected, $actual);
    }

    // Dataprovider

    /**
     * @return array<mixed,mixed>
     */
    public function providerBreakSystem(): array
    {
        return [
            'Default' => [self::CODE_0, self::MSG_EMPTY, Emergency::ERR_CODE_DEFAULT],
            'Specific' => [self::CODE_123, self::MSG_02, self::CODE_123],
            'ErrorCodeToLow' => [self::CODE_NEG1, self::MSG_EMPTY, Emergency::ERR_CODE_DEFAULT],
            'ErrorCodeToHigh' => [self::CODE_255, self::MSG_EMPTY, Emergency::ERR_CODE_DEFAULT],
        ];
    }

    /**
     * @return array<mixed,mixed>
     */
    public function providerExceptionStop(): array
    {
        return [
            'Default' => [new \Exception(), Emergency::ERR_CODE_DEFAULT],
            'Specific' => [new \RuntimeException(),Emergency::ERR_CODE_DEFAULT],
            'ErrCode' => [new \RuntimeException(self::MSG_EMPTY, self::CODE_89), self::CODE_89],
            'ErrCodeToLow' => [new \RuntimeException(self::MSG_EMPTY, self::CODE_NEG1), Emergency::ERR_CODE_DEFAULT],
            'ErrCodeToHigh' => [new \RuntimeException(self::MSG_EMPTY, self::CODE_255), Emergency::ERR_CODE_DEFAULT],
            'ErrMsg' => [new \Error(self::MSG_01), Emergency::ERR_CODE_DEFAULT],
            'ErrMsgCode' => [new \Error(self::MSG_01, self::CODE_123), self::CODE_123],
            ];
    }
}
