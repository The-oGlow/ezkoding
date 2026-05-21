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

namespace ollily\Tools\Batch;

use Ds\Map;
use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;

class BatchTaskHelper
{
    /** Default key for a tasklist. */
    public const DEFAULT = 'DEFAULT';

    /** @var \Ds\Map<mixed,TaskList> */
    private static $tasklists;

    /** @var LoggerInterface */
    private static $logger;

    private function __construct()
    {
        self::init();
    }

    public static function init(): void
    {
        /**
         * @psalm-suppress DocblockTypeContradiction
         * @phpstan-ignore function.impossibleType
         */
        if (is_null(self::$logger)) {
            self::$logger = EasyGoingLogger::init(BatchTaskHelper::class);
        }
        /**
         * @psalm-suppress DocblockTypeContradiction
         * @phpstan-ignore function.impossibleType
         */
        if (is_null(self::$tasklists)) {
            self::$tasklists = new Map();
        }
    }

    /**
     * @param null|string $listKey
     *
     * @return TaskList
     */
    public static function getTaskList(?string $listKey): TaskList
    {
        self::init();

        self::$logger->debug('START - listKey', [$listKey]);

        $listKey = $listKey ?? self::DEFAULT;
        if (!self::$tasklists->hasKey($listKey)) {
            self::$tasklists->put($listKey, new TaskList($listKey));
        }

        self::$logger->debug('END');

        return self::$tasklists->get($listKey);
    }

    /**
     * @param string      $fileName
     * @param null|string $listKey
     *
     * @return TaskList
     */
    public static function readTaskList(string $fileName, ?string $listKey)
    {
        self::init();

        self::$logger->debug('START - listKey,fileName', [$listKey, $fileName]);

        $listKey = $listKey ?? self::DEFAULT;
        if (file_exists($fileName)) {
            $taskList = self::getTaskList($listKey);
            $taskList->readFile($fileName);
        } else {
            self::$logger->warning('File does not exists!', [$fileName]);
            $taskList = self::getTaskList($listKey);
        }

        self::$logger->debug('END');

        return $taskList;
    }
}
