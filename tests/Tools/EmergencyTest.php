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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmergencyTest extends TestCase
{
    public const int CODE_NEG1     = -1;

    public const int CODE_0        = 0;

    public const int CODE_89       = 89;

    public const int CODE_123      = 123;

    public const int CODE_255      = 255;

    public const string MSG_EMPTY = '';

    public const string MSG_01    = 'ERR-MSG';

    public const string MSG_02    = 'There is something worried';

    #[DataProvider('providerBreakSystem')]
    public function testBreakSystem(int $errCode, string $errMessage, int $expected): void
    {
        $actual = Emergency::breakSystem($errCode, $errMessage, true);

        self::assertEquals($expected, $actual);
    }

    #[DataProvider('providerExceptionStop')]
    public function testExceptionStop(\Throwable $throwable, int $expected): void
    {
        $actual = Emergency::exceptionStop($throwable, true);

        self::assertEquals($expected, $actual);
    }

    // Dataprovider

    /**
     * @return array<mixed,mixed>
     */
    public static function providerBreakSystem(): array
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
    public static function providerExceptionStop(): array
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
