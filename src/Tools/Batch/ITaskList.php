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

/**
 * @phpstan-type TTaskListId mixed
 * @phpstan-type TTaskItem ITaskItem
 */
interface ITaskList
{
    public const string DEFAULT_ITEM_SEP = ';';

    public const string DEFAULT_CHARSET = 'UTF-8';

    public const string DEFAULT_LINE_END = "\n";

    public const bool DEFAULT_WITH_DATA_ITEM_ID = false;

    /**
     * @return mixed The unique identifier of this task list
     *
     * @phpstan-return TTaskListId
     */
    public function getListId(): mixed;

    /**
     * @return IBatchConfig The full config for this list
     */
    public function getListConfig(): IBatchConfig;

    /**
     * @param ITaskItem $taskItem Add a new task
     *
     * @phpstan-param TTaskItem $taskItem
     */
    public function addTask(ITaskItem $taskItem): void;

    /**
     * @return null|ITaskItem The task item on top of the queue or null
     *
     * @phpstan-return TTaskItem
     */
    public function nextTask(): ?ITaskItem;

    /**
     * @return int The number of tasks in this list
     */
    public function count(): int;

    /**
     * @return bool TRUE=The list is empty, else FALSE
     */
    public function isEmpty(): bool;

    /**
     * @return bool TRUE=Each data item has an unique identifier
     */
    public function isWithDataItemId(): bool;

    /**
     * @param string $fileName The full filename to the csv file
     *
     * @return bool TRUE=Reading the file was successfull, else FALSE
     */
    public function readFile(string $fileName): bool;

    /**
     * @param string $fileName The full filename to write the tasklist as csv file
     *
     * @return bool TRUE=Writing the file was successfull, else FALSE
     */
    public function storeFile(string $fileName): bool;
}
