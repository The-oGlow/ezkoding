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

use loophp\collection\Collection;
use ollily\Tools\String\ToStringTrait;

/**
 * @phpstan-import-type TTaskItemId from ITaskItem
 * @phpstan-import-type TDataKey from ITaskItem
 * @phpstan-import-type TDataValue from ITaskItem
 */
class TaskItem implements ITaskItem
{
    use ToStringTrait;

    /** @var mixed
     *  @phpstan-var TTaskItemId $itemId */
    private mixed $itemId = '';

    /** @var Collection<mixed,mixed>
     *  @phpstan-var Collection<TDataKey,TDataValue> $data */
    private Collection $data;

    /**
     * @param mixed                   $itemId
     * @param Collection<mixed,mixed> $data
     *
     * @phpstan-param TTaskItemId                     $itemId
     * @phpstan-param Collection<TDataKey,TDataValue> $data
     */
    public function __construct(mixed $itemId, Collection $data)
    {
        $this->itemId = $itemId;
        $this->data = $data;
    }

    #[\Override]
    public function getItemId(): mixed
    {
        return $this->itemId;
    }

    #[\Override]
    public function getData(): Collection
    {
        return $this->data;
    }

    #[\Override]
    public function getDataKeys(): Collection
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
    protected function __toStringValues(): mixed // NOSONAR: php:S100
    {
        return $this->data;
    }
}
