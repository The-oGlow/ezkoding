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
use Ds\Set;
use ollily\Tools\String\ToStringTrait;

/**
 * @phpstan-import-type TaskKey from ITaskItem
 * @phpstan-import-type DataKey from ITaskItem
 * @phpstan-import-type TaskData from ITaskItem
 */
class TaskItem implements ITaskItem
{
    use ToStringTrait;

    /** @phpstan-var TaskKey */
    private mixed $key = '';

    /** @phpstan-var TaskData */
    private Map $data;

    private IItemConfig $itemConfig;

    /**
     * @param mixed            $key
     * @param Map<mixed,mixed> $data
     * @param IItemConfig      $itemConfig
     *
     * @phpstan-param TaskKey  $key
     * @phpstan-param TaskData $data
     */
    public function __construct(mixed $key, Map $data, IItemConfig $itemConfig)
    {
        $this->key = $key;
        $this->data = $data;
        $this->itemConfig = $itemConfig;
    }

    #[\Override]
    public function getKey(): mixed
    {
        return $this->key;
    }

    #[\Override]
    public function getData(): Map
    {
        return $this->data;
    }

    #[\Override]
    public function getDataKeys(): Set
    {
        return $this->data->keys();
    }

    #[\Override]
    public function getDataValue(mixed $dataKey): mixed
    {
        $value = '';

        if ($this->isDataKeyExist($dataKey)) {
            $value = $this->data[$dataKey];
        }

        return $value;
    }

    #[\Override]
    public function isDataKeyExist(mixed $dataKey): bool
    {
        return $this->data->hasKey($dataKey);
    }

    #[\Override]
    public function empty(): bool
    {
        return $this->data->isEmpty();
    }

    #[\Override]
    public function count(): int
    {
        return $this->data->count();
    }

    #[\Override]
    public function getItemConfig(): IItemConfig
    {
        return $this->itemConfig;
    }

    #[\Override]
    public function getConfig(mixed $key): mixed
    {
        return $this->itemConfig->getConfig($key);
    }

    /**
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     */
    #[\Override]
    protected function __toStringValues(): mixed // NOSONAR: php:S100
    {
        return $this->data;
    }
}
