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

class EasyGoingLogger
{
    public const string DEFAULT_LEVEL = LogLevel::INFO;

    public const string LOGGER_DEFAULT = 'Monolog\Logger';

    public const string LOGGER_CONSOLE = 'Monolog\ConsoleLogger';

    public const string LOGGER_NULL = 'Psr\Log\NullLogger';

    /** @var array<mixed,mixed> LOGGER_CHOICE */
    private const array LOGGER_CHOICE = [self::LOGGER_CONSOLE, self::LOGGER_DEFAULT, self::LOGGER_NULL];

    private function __construct()
    {
        // Hide public constructor
    }

    /**
     * @param string             $name
     * @param string             $level
     * @param array<mixed,mixed> $handlers
     * @param array<mixed,mixed> $processors
     * @param ?DateTimeZone      $timezone
     *
     * @return LoggerInterface
     */
    public static function init(
        string $name,
        string $level = self::DEFAULT_LEVEL,
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
        if (empty($clazzName)) {
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
                $instance = $refClazz->newInstance($name, $handlers, $processors, $timezone, $level);
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
