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
    private array $data = [];

    /**
     * @param mixed              $key
     * @param array<mixed,mixed> $data
     *
     * @phpstan-param TaskKey  $key
     * @phpstan-param TaskData $data
     */
    public function __construct(mixed $key, array $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    #[\Override]
    public function getKey(): mixed
    {
        return $this->key;
    }

    #[\Override]
    public function getData(): array
    {
        return $this->data;
    }

    #[\Override]
    public function getDataKeys(): Set
    {
        $dataKeys = new Set();
        if (is_array($this->data)) {
            $dataKeys = new Set(array_keys($this->data));
        }

        return $dataKeys;
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
        $exists = false;

        if (is_array($this->data)) {
            $exists = array_key_exists($dataKey, $this->data);
        }

        return $exists;
    }

    #[\Override]
    public function empty(): bool
    {
        return empty($this->data);
    }

    #[\Override]
    public function count(): int
    {
        return is_array($this->data) ? count($this->data) : 0;
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
