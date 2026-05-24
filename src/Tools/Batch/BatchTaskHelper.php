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
    public const string DEFAULT = 'DEFAULT';

    /** @var Map<mixed,TaskList> */
    private static ?Map $tasklists = null;

    private static ?LoggerInterface $logger = null;

    private function __construct()
    {
        self::init();
    }

    public static function init(): void
    {
        if (is_null(self::$logger)) {
            self::$logger = EasyGoingLogger::init(BatchTaskHelper::class);
        }
        if (is_null(self::$tasklists)) {
            self::$tasklists = new Map();
        }
    }

    /**
     * @return LoggerInterface
     *
     * @psalm-suppress InvalidNullableReturnType
     */
    private static function logger(): LoggerInterface
    {
        /**
         * @psalm-suppress NullableReturnStatement
         * @phpstan-ignore return.type
         */
        return self::$logger;
    }

    /**
     * @return Map<mixed,TaskList>
     *
     * @psalm-suppress InvalidNullableReturnType
     */
    private static function taskLists(): Map
    {
        /**
         * @psalm-suppress NullableReturnStatement
         * @phpstan-ignore return.type
         */
        return self::$tasklists;
    }

    public static function getTaskList(string $listKey = self::DEFAULT): TaskList
    {
        self::init();
        self::logger()->debug('START - listKey', [$listKey]);

        $listKey = empty($listKey) ? self::DEFAULT : $listKey;
        if (!self::taskLists()->hasKey($listKey)) {
            self::taskLists()->put($listKey, new TaskList($listKey));
        }

        self::logger()->debug('END');

        return self::taskLists()->get($listKey);
    }

    public static function readTaskList(string $fileName, string $listKey = self::DEFAULT): TaskList
    {
        self::init();
        self::logger()->debug('START - listKey,fileName', [$listKey, $fileName]);

        $listKey = empty($listKey) ? self::DEFAULT : $listKey;
        if (file_exists($fileName)) {
            $taskList = self::getTaskList($listKey);
            $taskList->readFile($fileName);
        } else {
            self::logger()->warning('File does not exists!', [$fileName]);
            $taskList = self::getTaskList($listKey);
        }

        self::logger()->debug('END');

        return $taskList;
    }
}
