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

/**
 * @phpstan-type TaskKey mixed
 * @phpstan-type DataKey mixed
 * @phpstan-type TaskData Map<DataKey,mixed>
 */
interface ITaskItem extends \Stringable
{
    /**
     * return mixed.
     *
     * @phpstan-return TaskKey
     */
    public function getKey(): mixed;

    /**
     * return Map<mixed,mixed>.
     *
     * @phpstan-return TaskData
     */
    public function getData(): Map;

    /**
     * return Set.
     *
     * @phpstan-return Set<DataKey>
     */
    public function getDataKeys(): Set;

    /**
     * @param mixed $dataKey
     *
     * @phpstan-param DataKey $dataKey
     *
     * @return mixed
     */
    public function getDataValue(mixed $dataKey): mixed;

    /**
     * @param mixed $dataKey
     *
     * @phpstan-param DataKey $dataKey
     *
     * @return bool TRUE=Item is empty, else false
     */
    public function isDataKeyExist(mixed $dataKey): bool;

    /**
     * @return bool TRUE=Item is empty, else false
     */
    public function empty(): bool;

    /**
     * @return int Count of columns
     */
    public function count(): int;

    /**
     * @return IItemConfig
     */
    public function getItemConfig(): IItemConfig;

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function getConfig(mixed $key): mixed;

    #[\Override]
    public function __toString(): string;
}
