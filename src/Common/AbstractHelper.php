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

namespace ollily\Common;

use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Abstract implementation for a helper clazz.
 *
 * @author ollily
 */
abstract class AbstractHelper extends AbstractSingleton implements IHelper
{
    private static LoggerInterface $logger;

    /**
     * Protected constructor.
     *
     * @param bool                   $withLogger TRUE=activate logging, else FALSE
     * @param int|LogLevel::*|string $level      The minimum logging level at which this handler will be triggered (Default: {@link self::LEVEL_DEFAULT})
     */
    protected function __construct(bool $withLogger = true, int|LogLevel|string $level = self::LEVEL_DEFAULT)
    {
        if ($withLogger) {
            self::$logger = EasyGoingLogger::init(AbstractHelper::class, level: $level);
        } else {
            self::$logger = EasyGoingLogger::init(AbstractHelper::class, level: EasyGoingLogger::DISABLE_LEVEL);
        }
        self::$logger->debug('START');

        parent::__construct($withLogger, $level);

        self::$logger->debug('END');
    }
}
