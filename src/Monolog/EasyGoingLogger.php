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

use DateTimeZone;
use ollily\Tools\Emergency;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * The only logger you will ever need.
 *
 * @author ollily
 */
class EasyGoingLogger
{
    /** @var string Default output level */
    public const string DEFAULT_LEVEL = LogLevel::INFO;

    /** @var string This level disables the logging */
    public const string DISABLE_LEVEL = '';

    /** @var string Logging to console */
    public const string LOGGER_CONSOLE = 'Monolog\ConsoleLogger';

    /** @var string Logger, which loggs nothing */
    public const string LOGGER_NOTHING = 'Monolog\DoNothingLogger';

    /** @var string Monolog standard logger */
    public const string LOGGER_DEFAULT = 'Monolog\Logger';

    /** @var string PSR null logger */
    public const string LOGGER_NULL = 'Psr\Log\NullLogger';

    /** @var array<mixed> LOGGER_CHOICE */
    private const array LOGGER_CHOICE = [self::LOGGER_CONSOLE, self::LOGGER_DEFAULT, self::LOGGER_NOTHING, self::LOGGER_NULL];

    private function __construct()
    {
        // Hide public constructor
    }

    /**
     * Creates a new logging instance.
     *
     * @param string                 $name       The class for which the logger is assigned to
     * @param int|LogLevel::*|string $level      The minimum logging level at which this handler will be triggered (Default: {@link self::LEVEL_DEFAULT})
     * @param array<mixed>           $handlers   The list of logger handler (Default: empty)
     * @param array<mixed>           $processors The list of logging processors (Default: empty)
     * @param ?DateTimeZone          $timezone   The timezone to log (Default: null)
     *
     * @return LoggerInterface The new logger
     */
    public static function init(
        string $name,
        int|LogLevel|string $level = self::DEFAULT_LEVEL,
        array $handlers = [],
        array $processors = [],
        ?DateTimeZone $timezone = null
    ): LoggerInterface {
        /** @var mixed $instance */
        $instance = null;

        /** @phpstan-var class-string<LoggerInterface> $clazzName */
        foreach (self::LOGGER_CHOICE as $clazzName) {
            if (class_exists($clazzName)) {
                break;
            }
        }
        if (empty($clazzName) || self::DISABLE_LEVEL == $level) {
            $clazzName = self::LOGGER_NULL;
        }

        try {
            /**
             * @psalm-suppress ArgumentTypeCoercion
             * @phpstan-ignore argument.type
             */
            $refClazz = new \ReflectionClass($clazzName);
            if (is_null($refClazz->getConstructor())) {
                $instance = $refClazz->newInstance();
            } else {
                if (self::LOGGER_CONSOLE == $clazzName) {
                    $instance = $refClazz->newInstance($name, $handlers, $processors, $timezone, $level);
                } else {
                    $instance = $refClazz->newInstance($name, $handlers, $processors, $timezone);
                }
            }
        } catch (\ReflectionException $refExp) {
            Emergency::exceptionStop($refExp);
        }

        /**
         * @psalm-suppress LessSpecificReturnStatement
         */
        return $instance;
    }
}
