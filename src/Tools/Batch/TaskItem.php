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
 * @phpstan-import-type TTaskItemId from ITaskItem
 * @phpstan-import-type TDataKey from ITaskItem
 * @phpstan-import-type TDataValue from ITaskItem
 */
class TaskItem implements ITaskItem
{
    use ToStringTrait;

    /** @var TTaskItemId */
    private mixed $itemId = '';

    /** @var Map<TDataKey,TDataValue> */
    private Map $data;

    /**
     * @param TTaskItemId              $itemId
     * @param Map<TDataKey,TDataValue> $data
     */
    public function __construct(mixed $itemId, Map $data)
    {
        $this->itemId = $itemId;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getItemId(): mixed
    {
        return $this->itemId;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getData(): Map
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     *
     * @phpstan-ignore missingType.generics
     */
    #[\Override]
    public function getDataKeys(): Set
    {
        return $this->data->keys();
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getDataValue(mixed $dataKey): mixed
    {
        $value = '';

        if ($this->isDataKeyExist($dataKey)) {
            $value = $this->data[$dataKey];
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function isDataKeyExist(mixed $dataKey): bool
    {
        return $this->data->hasKey($dataKey);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function empty(): bool
    {
        return $this->data->isEmpty();
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function count(): int
    {
        return $this->data->count();
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function __toStringValues(): mixed // NOSONAR: php:S100
    {
        return $this->data;
    }
}
