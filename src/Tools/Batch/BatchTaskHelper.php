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

/**
 * @phpstan-import-type TTaskListId from ITaskList
 * @phpstan-import-type TTaskItemId from ITaskItem
 */
class BatchTaskHelper
{
    /** Default key for a tasklist. */
    public const string DEFAULT = 'DEFAULT';

    public const bool DEFAULT_WITH_DATA_KEYS = TaskList::DEFAULT_WITH_DATA_ITEM_ID;

    public const int COL_PAGEID = 0;

    public const int COL_TITLE = 1;

    private static bool $isInit = false;

    /** @var Map<mixed,TaskList>
     * @phpstan-var Map<TTaskListId,TaskList> */
    protected static Map $tasklists;

    protected static LoggerInterface $logger;

    protected static IBatchConfig $defaultConfig;

    private function __construct()
    {
        self::init();
    }

    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     */
    public static function init(): void
    {
        if (!self::$isInit) {
            if (!isset(self::$logger)) {
                self::$logger = EasyGoingLogger::init(BatchTaskHelper::class);
            }
            if (!isset(self::$tasklists)) {
                self::$tasklists = new Map();
            }
            if (!isset(self::$defaultConfig)) {
                self::$defaultConfig = new BatchConfig(new Map());
            }
            self::$isInit = true;
        }
    }

    /**
     * @return LoggerInterface
     */
    private static function logger(): LoggerInterface
    {
        self::init();

        return self::$logger;
    }

    /**
     * @return Map<mixed,TaskList>
     *
     * @phpstan-return Map<TTaskListId,TaskList>
     */
    private static function taskLists(): Map
    {
        self::init();

        return self::$tasklists;
    }

    /**
     * @return IBatchConfig
     */
    private static function defaultConfig(): IBatchConfig
    {
        self::init();

        return self::$defaultConfig;
    }

    /**
     * @param mixed         $listId
     * @param ?IBatchConfig $listConfig
     * @param bool          $withHeader TRUE=with heade columns, else FALSE (only used, if tasklist will be newly created)
     *
     * @phpstan-param $listId TTaskListId
     *
     * @return TaskList
     */
    public static function getTaskList(
        mixed $listId = self::DEFAULT,
        ?IBatchConfig $listConfig = null,
        bool $withHeader = self::DEFAULT_WITH_DATA_KEYS
    ): TaskList {
        self::init();
        self::logger()->debug('START - listId', [$listId]);

        $listId = empty($listId) ? self::DEFAULT : $listId;
        $listConfig = empty($listConfig) ? self::defaultConfig() : $listConfig;
        if (!self::taskLists()->hasKey($listId)) {
            self::taskLists()->put($listId, new TaskList($listId, $listConfig, $withHeader));
        }

        self::logger()->debug('END');

        return self::taskLists()->get($listId);
    }

    /**
     * @param string        $fileName
     * @param ?IBatchConfig $listConfig
     * @param mixed         $listId
     * @param bool          $withHeader TRUE=with heade columns, else FALSE
     *
     * @phpstan-param TTaskListId $listId
     *
     * @return TaskList
     */
    public static function readTaskList(
        string $fileName,
        ?IBatchConfig $listConfig = null,
        mixed $listId = self::DEFAULT,
        bool $withHeader = self::DEFAULT_WITH_DATA_KEYS
    ): TaskList {
        self::init();
        self::logger()->debug('START - listKey,fileName', [$listId, $fileName]);

        $listId = empty($listId) ? self::DEFAULT : $listId;
        if (file_exists($fileName)) {
            $taskList = self::getTaskList($listId, $listConfig, $withHeader);
            $taskList->readFile($fileName);
        } else {
            self::logger()->warning('File does not exists', [$fileName]);
            $taskList = self::getTaskList($listId, $listConfig, $withHeader);
        }

        self::logger()->debug('END');

        return $taskList;
    }
}
