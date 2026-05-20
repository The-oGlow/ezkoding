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

use Monolog\Formatter\EasyGoingFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Monolog\Processor\PaddingProcessor;
use Psr\Log\LoggerInterface;

/**
 * Utility class to stop an application.
 */
class Emergency
{
    /** Default message */
    public const  MSG_DEFAULT = 'Undefined reason to stop now!';

    /** Default code */
    public const  ERR_CODE_DEFAULT = 1;

    /** Maximum code which is allowed. */
    private const ERR_CODE_MAX = 254;

    /** @var LoggerInterface */
    private static $logger;

    private function __construct()
    {
        self::init();
    }

    private static function init(): void
    {
        /**
         * @psalm-suppress DocblockTypeContradiction
         * @phpstan-ignore function.impossibleType
         *  */
        if (is_null(self::$logger)) {
            $handler = new ErrorLogHandler();
            $handler->setFormatter(new EasyGoingFormatter());
            self::$logger = new Logger(Emergency::class, [$handler]);
            self::$logger->pushProcessor(new PaddingProcessor());
        }
    }

    /**
     * @return LoggerInterface
     */
    private static function getLogger(): LoggerInterface
    {
        self::init();

        return self::$logger;
    }

    /**
     * Immediately stopping the application caused by an exception. As a hmomage to "Knight Rider".
     *
     * @param \Throwable $throwable The exception which is thrown to end the application
     * @param bool       $unitTest  TRUE=don't call exit(), it's an unit test, FALSE= Standard termination (Default: FALSE)
     *
     * @return int The error code for ending the application
     *
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    public static function exceptionStop(\Throwable $throwable, bool $unitTest = false): int
    {
        $errMsg = sprintf('\%s %s', get_class($throwable), $throwable->getMessage());

        /** @psalm-suppress PossiblyInvalidArgument */
        return static::breakSystem($throwable->getCode(), $errMsg, $unitTest);
    }

    /**
     * Immediately stopping the application. As a hmomage to "Knight Rider"'s "Emergency Break System".
     *
     * @param int    $errorCode    The error code for ending the application
     * @param string $errorMessage The error message for ending the application
     * @param bool   $unitTest     TRUE=don't call exit(), it's an unit test, FALSE= Standard termination (Default: FALSE)
     *
     * @return int The error code for ending the application
     *
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    public static function breakSystem(int $errorCode = self::ERR_CODE_DEFAULT, string $errorMessage = '', bool $unitTest = false): int
    {
        if ($errorCode < self::ERR_CODE_DEFAULT || $errorCode > self::ERR_CODE_MAX) {
            $errorCode = self::ERR_CODE_DEFAULT;
        }
        if (empty($errorMessage)) {
            $errorMessage = self::MSG_DEFAULT;
        }
        self::getLogger()->emergency($errorMessage, [$errorCode]);
        if (!$unitTest) {
            die($errorCode); // NOSONAR:php:S1799
        } else {
            return $errorCode;
        }
    }
}
