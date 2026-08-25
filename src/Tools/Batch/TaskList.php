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
use Ds\Vector;
use Monolog\EasyGoingLogger;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type TTaskListId from ITaskList
 * @phpstan-import-type TTaskItemId from ITaskItem
 * @phpstan-import-type TDataKey from ITaskItem
 */
class TaskList implements ITaskList
{
    protected const string LINE_ENDS = "/(\r|\n|\r\n)/";

    private static LoggerInterface $logger;

    /** @var mixed
     * @phpstan-var TTaskListId $listId */
    private mixed $listId;

    /** @var Vector<mixed>
     * @phpstan-var Vector<TDataKey> $dataItemIds */
    private Vector $dataItemIds;

    private bool $isDataItemIdRead = false;

    private bool $withDataItemId = self::DEFAULT_WITH_DATA_ITEM_ID;

    private IBatchConfig $listConfig;

    /** @var Queue<ITaskItem> */
    private Queue $tasks;

    /**
     * @param mixed        $listId
     * @param IBatchConfig $listConfig
     * @param bool         $withDataItemId
     *
     * @phpstan-param TTaskListId $listId
     */
    public function __construct(mixed $listId, IBatchConfig $listConfig, bool $withDataItemId = self::DEFAULT_WITH_DATA_ITEM_ID)
    {
        self::$logger = EasyGoingLogger::init(TaskList::class);
        $this->listId = $listId;
        $this->withDataItemId = $withDataItemId;
        $this->listConfig = $listConfig;
        $this->tasks = new Queue();
        $this->dataItemIds = new Vector();
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function getListConfig(): IBatchConfig
    {
        return $this->listConfig;
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function addTask(ITaskItem $taskItem): void
    {
        $this->tasks->push($taskItem);
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function nextTask(): ?ITaskItem
    {
        $taskItem = null;
        if (!$this->isEmpty()) {
            $taskItem = $this->tasks->pop();
        }

        return $taskItem;
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function count(): int
    {
        return $this->tasks->count();
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function isEmpty(): bool
    {
        return $this->tasks->isEmpty();
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function isWithDataItemId(): bool
    {
        return $this->withDataItemId;
    }

    /**
     * {@inheritDoc].
     */
    #[\Override]
    public function readFile(string $fileName): bool
    {
        self::$logger->debug('START - fileName', [$fileName]);

        $fileRead = false;

        if (!empty($fileName)) {
            $fHandle = fopen($fileName, 'r');
            if (is_resource($fHandle)) {
                if ($this->withDataItemId && !$this->isDataItemIdRead) {
                    $line = fgets($fHandle);
                    if (!is_bool($line)) {
                        $rawIdLine = mb_convert_encoding($line, self::DEFAULT_CHARSET);
                        $this->parseDataItemIds($rawIdLine);
                    }
                }
                $idx = 0;
                while ($line = fgets($fHandle)) {
                    $rawDataLine = mb_convert_encoding($line, self::DEFAULT_CHARSET);
                    $taskItemId = $this->listId . $idx;
                    $newTask = $this->parseTaskData($taskItemId, $rawDataLine);
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
     * {@inheritDoc].
     */
    #[\Override]
    public function storeFile(string $fileName): bool
    {
        self::$logger->debug('START - fileName', [$fileName]);

        $fileStored = false;

        if (!empty($fileName)) {
            $fHandle = fopen($fileName, 'w');
            if (is_resource($fHandle)) {
                if ($this->withDataItemId && $this->isDataItemIdRead) {
                    $rawIdLine = implode(self::DEFAULT_ITEM_SEP, $this->dataItemIds->toArray());
                    fwrite($fHandle, $rawIdLine);
                }

                while (!$this->isEmpty()) {
                    $rawDataLine = $this->nextTask() ?? '';
                    if ($rawDataLine instanceof ITaskItem) {
                        $rawDataLine = $rawDataLine->__toString();
                    }
                    $convertedLine = mb_convert_encoding($rawDataLine, self::DEFAULT_CHARSET);
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

    /**
     * @param mixed $taskItemId
     * @param mixed $taskDataLine The raw taskdata
     *
     * @phpstan-param TTaskItemId $taskItemId
     */
    protected function parseTaskData(mixed $taskItemId, mixed $taskDataLine): ?ITaskItem
    {
        self::$logger->debug('START - taskItemId', [$taskItemId]);

        $newTask = null;
        if (is_string($taskDataLine)) {
            $rawDataLine = preg_filter(self::LINE_ENDS, '', $taskDataLine);
            self::$logger->debug('newLine', [$rawDataLine]);
            /** @psalm-suppress RiskyTruthyFalsyComparison */
            if (!empty($rawDataLine)) {
                $taskData = new Map();
                if ($this->withDataItemId && $this->isDataItemIdRead) {
                    $taskData->putAll(array_combine($this->dataItemIds->toArray(), explode(self::DEFAULT_ITEM_SEP, $rawDataLine)));
                } else {
                    $taskData->putAll(explode(self::DEFAULT_ITEM_SEP, $rawDataLine));
                }
                $newTask = new TaskItem($taskItemId, $taskData);
            }
        }
        self::$logger->debug('newTask', [$newTask]);
        self::$logger->debug('END');

        return $newTask;
    }

    /**
     * @param mixed $dataKeysLine The raw dataKeys
     *
     * @return bool TRUE=Parsing was successfull, else FALSE
     */
    public function parseDataItemIds(mixed $dataKeysLine): bool
    {
        self::$logger->debug('START - itemKey');

        if ($this->withDataItemId && !$this->isDataItemIdRead && is_string($dataKeysLine)) {
            $newLine = preg_filter(self::LINE_ENDS, '', $dataKeysLine);
            /** @psalm-suppress RiskyTruthyFalsyComparison */
            if (!empty($newLine)) {
                $this->dataItemIds = new Vector(explode(self::DEFAULT_ITEM_SEP, $newLine));
            }
            $this->isDataItemIdRead = true;
        }

        self::$logger->debug('END');

        return $this->isDataItemIdRead;
    }
}
