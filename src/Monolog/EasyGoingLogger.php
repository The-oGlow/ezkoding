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
    private const LOGGER_DEFAULT = 'Monolog\Logger';

    private const LOGGER_CONSOLE = 'Monolog\ConsoleLogger';

    private const LOGGER_NULL    = 'Psr\Log\NullLogger';

    private function __construct()
    {
        // Hide public constructor
    }

    /**
     * @param string              $name
     * @param string              $level
     * @param array <mixed,mixed> $handlers
     * @param array <mixed,mixed> $processors
     * @param null|DateTimeZone   $timezone
     *
     * @return LoggerInterface
     *
     * @psalm-suppress InvalidNullableReturnType
     */
    public static function init(
        string $name,
        string $level = LogLevel::INFO,
        array $handlers = [],
        array $processors = [],
        ?DateTimeZone $timezone = null
    ): LoggerInterface {
        /** @psalm-var class-string<LoggerInterface> */
        $clazzName = self::LOGGER_NULL;
        if (class_exists(self::LOGGER_CONSOLE)) {
            $clazzName = self::LOGGER_CONSOLE;
        } elseif (class_exists(self::LOGGER_DEFAULT)) {
            $clazzName = self::LOGGER_DEFAULT;
        }

        $instance = null;

        try {
            /**
             * @psalm-suppress ArgumentTypeCoercion
             * @phpstan-ignore argument.type
             */
            $refClazz = new \ReflectionClass($clazzName);
            if (is_null($refClazz->getConstructor())) {
                $instance = $refClazz->newInstance();
            } else {
                $instance = $refClazz->newInstance($name, $level, $handlers, $processors, $timezone);
            }
        } catch (\ReflectionException $refExp) {
            Emergency::exceptionStop($refExp);
        }

        /**
         * @psalm-suppress NullableReturnStatement
         * @phpstan-ignore return.type
         */
        return $instance;
    }
}
