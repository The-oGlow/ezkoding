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

use Ds\Queue;
use Monolog\ConsoleLogger;
use Psr\Log\LoggerInterface;

class TaskList
{
    /** @var LoggerInterface */
    private static $logger;

    /** @var string */
    private $listKey;

    /** @var Queue<ITaskItem> */
    private $tasks;

    public function __construct(string $listKey)
    {
        self::$logger = new ConsoleLogger(TaskList::class);
        $this->listKey = $listKey;
        $this->tasks = new Queue();
    }

    public function getListKey(): string
    {
        return $this->listKey;
    }

    public function addTask(ITaskItem $task): void
    {
        $this->tasks->push($task);
    }

    /**
     * @return null|ITaskItem
     */
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

    /**
     * @param string $fileName
     *
     * @return bool
     */
    public function readFile(string $fileName): bool
    {
        self::$logger->debug('START - fileName', [$fileName]);

        $fileRead = false;

        if (!empty($fileName)) {
            $fHandle = fopen($fileName, 'r');
            if (is_resource($fHandle)) {
                $idx = 0;
                while ($line = fgets($fHandle, 1000)) {
                    $convertedLine = mb_convert_encoding($line, 'UTF-8');
                    $itemKey = $this->listKey . $idx;
                    /** @var null|ITaskItem $newTask */
                    $newTask = $this->parseTaskData($itemKey, $convertedLine);
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

    /**
     * @param mixed $itemKey
     * @param mixed $convertedLine
     *
     * @return null|ITaskItem
     */
    protected function parseTaskData($itemKey, $convertedLine): ?ITaskItem
    {
        self::$logger->debug('START - itemKey', [$itemKey]);

        /** @var null|ITaskItem $newTask */
        $newTask = null;
        if (is_string($convertedLine)) {
            $newLine = preg_filter("/(\r|\n|\r\n)/", '', $convertedLine);
            self::$logger->debug('newLine', [$newLine]);
            /** @psalm-suppress RiskyTruthyFalsyComparison */
            if (!empty($newLine)) {
                $newTask = new TaskItem($itemKey, explode(';', $newLine));
            }
        }
        self::$logger->debug('newTask', [$newTask]);
        self::$logger->debug('END');

        return $newTask;
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
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
                    $convertedLine = mb_convert_encoding($line, 'UTF-8') . "\n";
                    fwrite($fHandle, $convertedLine);
                }
                fclose($fHandle);
                $fileStored = true;
            }
        }

        self::$logger->debug('END');

        return $fileStored;
    }
}
