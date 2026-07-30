<?php

/*
 * Copyright 2026 postm.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
    public function getListId(): mixed
    ;

    /**
     * @return IBatchConfig The full config for this list
     */
    public function getListConfig(): IBatchConfig;

    /**
     * @param ITaskItem $taskItem Add a new task
     * 
     * @phpstan-param TTaskItem $taskItem
     */
    public function addTask(ITaskItem $taskItem): void
    ;

    /**
     * @return ITaskItem|null The task item on top of the queue or null
     * 
     * @phpstan-return TTaskItem
     */
    public function nextTask(): ?ITaskItem
    ;

    /**
     * @return int The number of tasks in this list
     */
    public function count(): int
    ;

    /**
     * @return bool TRUE=The list is empty, else FALSE
     */
    public function isEmpty(): bool
    ;

    /**
     * @return bool TRUE=Each data item has an unique identifier
     */
    public function isWithDataItemId(): bool
    ;

    /**
     * @param string $fileName The full filename to the csv file
     * @return bool TRUE=Reading the file was successfull, else FALSE
     */
    public function readFile(string $fileName): bool 
    ;

    /**
     * @param string $fileName The full filename to write the tasklist as csv file
     * @return bool TRUE=Writing the file was successfull, else FALSE
     */
    public function storeFile(string $fileName): bool
    ;
}
