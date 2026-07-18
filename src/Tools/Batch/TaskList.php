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
use Ds\Queue;
use Ds\Set;
use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;

class TaskList
{
    public const string ITEM_SEP = ';';

    public const string DEFAULT_CHARSET = 'UTF-8';

    public const string DEFAULT_LINE_END = "\n";

    public const bool DEFAULT_WITH_DATA_KEYS = false;

    public const string LINE_ENDS = "/(\r|\n|\r\n)/";

    private static LoggerInterface $logger;

    private string $listKey;

    private bool $withDataKeys = self::DEFAULT_WITH_DATA_KEYS;

    /** @var Set<string> */
    private Set $dataKeys;

    private bool $dataKeysRead = false;

    /** @var Queue<ITaskItem> */
    private Queue $tasks;

    public function __construct(string $listKey, bool $withDataKeys = self::DEFAULT_WITH_DATA_KEYS)
    {
        self::$logger = EasyGoingLogger::init(TaskList::class);
        $this->listKey = $listKey;
        $this->withDataKeys = $withDataKeys;
        $this->tasks = new Queue();
        $this->dataKeys = new Set();
    }

    public function getListKey(): string
    {
        return $this->listKey;
    }

    public function addTask(ITaskItem $task): void
    {
        $this->tasks->push($task);
    }

    public function nextTask(): ?ITaskItem
    {
        $task = null;
        if (!$this->isEmpty()) {
            $task = $this->tasks->pop();
        }

        return $task;
    }

    public function count(): int
    {
        return $this->tasks->count();
    }

    public function isEmpty(): bool
    {
        return $this->tasks->isEmpty();
    }

    public function isWithDataKeys(): bool
    {
        return $this->withDataKeys;
    }

    public function readFile(string $fileName, IItemConfig $itemConfig): bool
    {
        self::$logger->debug('START - fileName', [$fileName]);

        $fileRead = false;

        if (!empty($fileName)) {
            $fHandle = fopen($fileName, 'r');
            if (is_resource($fHandle)) {
                if ($this->withDataKeys && !$this->dataKeysRead) {
                    $line = fgets($fHandle);
                    if (!is_bool($line)) {
                        $convertedLine = mb_convert_encoding($line, self::DEFAULT_CHARSET);
                        $this->parseDataKeys($convertedLine);
                    }
                }
                $idx = 0;
                while ($line = fgets($fHandle)) {
                    $convertedLine = mb_convert_encoding($line, self::DEFAULT_CHARSET);
                    $itemKey = $this->listKey . $idx;
                    $newTask = $this->parseTaskData($itemKey, $convertedLine, $itemConfig);
                    if (!is_null($newTask)) {
                        $this->addTask($newTask);
                        $idx++;
                    }
                }
                fclose($fHandle);
                $fileRead = true;
            }
        }
        self::$logger->debug('END');

        return $fileRead;
    }

    protected function parseTaskData(mixed $itemKey, mixed $taskDataLine, IItemConfig $itemConfig): ?ITaskItem
    {
        self::$logger->debug('START - itemKey', [$itemKey]);

        $newTask = null;
        if (is_string($taskDataLine)) {
            $newLine = preg_filter(self::LINE_ENDS, '', $taskDataLine);
            self::$logger->debug('newLine', [$newLine]);
            /** @psalm-suppress RiskyTruthyFalsyComparison */
            if (!empty($newLine)) {
                $taskData = new Map();
                if ($this->withDataKeys && $this->dataKeysRead) {
                    // $newKeys = $this->dataKeys->toArray();
                    // $newValues = explode(self::ITEM_SEP, $newLine);
                    // $newData = array_combine($newKeys, $newValues);
                    $taskData->putAll(array_combine($this->dataKeys->toArray(), explode(self::ITEM_SEP, $newLine)));
                } else {
                    $taskData->putAll(explode(self::ITEM_SEP, $newLine));
                }
                $newTask = new TaskItem($itemKey, $taskData, $itemConfig);
            }
        }
        self::$logger->debug('newTask', [$newTask]);
        self::$logger->debug('END');

        return $newTask;
    }

    public function storeFile(string $fileName): bool
    {
        self::$logger->debug('START - fileName', [$fileName]);

        $fileStored = false;

        if (!empty($fileName)) {
            $fHandle = fopen($fileName, 'w');
            if (is_resource($fHandle)) {
                while (!$this->isEmpty()) {
                    $line = $this->nextTask() ?? '';
                    if ($line instanceof ITaskItem) {
                        $line = $line->__toString();
                    }
                    $convertedLine = mb_convert_encoding($line, self::DEFAULT_CHARSET);
                    /** @phpstan-ignore notIdentical.alwaysTrue */
                    if ($convertedLine !== false) {
                        $convertedLine .= self::DEFAULT_LINE_END;
                        fwrite($fHandle, $convertedLine);
                    }
                }
                fclose($fHandle);
                $fileStored = true;
            }
        }

        self::$logger->debug('END');

        return $fileStored;
    }

    public function parseDataKeys(mixed $dataKeysLine): void
    {
        self::$logger->debug('START - itemKey');

        if ($this->withDataKeys && !$this->dataKeysRead && is_string($dataKeysLine)) {
                $newLine = preg_filter(self::LINE_ENDS, '', $dataKeysLine);
                /** @psalm-suppress RiskyTruthyFalsyComparison */
                if (!empty($newLine)) {
                    $this->dataKeys = new Set(explode(self::ITEM_SEP, $newLine));
                }
                $this->dataKeysRead = true;
        }

        self::$logger->debug('END');
    }
}
